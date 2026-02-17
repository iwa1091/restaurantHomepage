<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Mail\AdminReservationNoticeMail;
use App\Mail\ReservationConfirmedMail;
use App\Models\AdminBlock;
use App\Models\BusinessHour;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\ScheduledEmail;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function checkAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => ['required', 'date_format:Y-m-d'],
            'party_size' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $date = Carbon::parse($request->date);
        $partySize = (int) $request->party_size;

        $tomorrow = now()->addDay()->startOfDay();
        if ($date->lt($tomorrow)) {
            return response()->json([
                'available_slots' => [],
                'message' => '当日のご予約はお電話にて承ります。',
            ], 200);
        }

        [$openTime, $closeTime, $bhMessage] = $this->resolveOpenCloseByBusinessHour($date);
        if (!$openTime || !$closeTime) {
            return response()->json([
                'available_slots' => [],
                'message' => $bhMessage ?: '本日は終日休業です。',
            ], 200);
        }

        $tatamiPair = $this->getTatamiPair();
        $availableSlots = [];
        $currentTime = $this->alignToHalfHour($openTime->copy());

        while ($currentTime->lt($closeTime)) {
            $slotEnd = $currentTime->copy()->addHours(2);
            if ($slotEnd->gt($closeTime)) {
                break;
            }

            if ($this->isBlockedByAdminBlock($date, $currentTime)) {
                $currentTime->addMinutes(30);
                continue;
            }

            if ($partySize <= 4) {
                $table = $this->findAvailableTableForSmallGroup($date, $currentTime, $partySize, null);
                if ($table) {
                    $availableSlots[] = [
                        'start' => $currentTime->format('H:i'),
                        'end' => $slotEnd->format('H:i'),
                    ];
                }
            } else {
                $canUseTatamiPair = $tatamiPair->count() >= 2
                    && $this->isTatamiPairAvailable($date, $currentTime, $tatamiPair->pluck('id')->all());

                if ($canUseTatamiPair) {
                    $availableSlots[] = [
                        'start' => $currentTime->format('H:i'),
                        'end' => $slotEnd->format('H:i'),
                    ];
                }
            }

            $currentTime->addMinutes(30);
        }

        return response()->json(['available_slots' => $availableSlots], 200);
    }

    public function store(StoreReservationRequest $request)
    {
        $date = Carbon::parse($request->date);
        $partySize = (int) $request->party_size;
        $seatPreference = $request->seat_preference;

        $tomorrow = now()->addDay()->startOfDay();
        if ($date->lt($tomorrow)) {
            return response()->json([
                'message' => '当日のご予約はお電話にて承ります。',
            ], 422);
        }

        $proposedStart = Carbon::parse($request->date . ' ' . $request->start_time);
        $proposedEnd = $proposedStart->copy()->addHours(2);

        if (((int) $proposedStart->format('i')) % 30 !== 0) {
            return response()->json([
                'message' => '開始時刻は30分刻みで選択してください。',
            ], 422);
        }

        [$openTime, $closeTime, $bhMessage] = $this->resolveOpenCloseByBusinessHour($date);
        if (!$openTime || !$closeTime) {
            return response()->json([
                'message' => $bhMessage ?: '本日は終日休業のため予約できません。',
            ], 422);
        }

        if ($proposedStart->lt($openTime) || $proposedEnd->gt($closeTime)) {
            return response()->json([
                'message' => '選択された時間は営業時間外です。',
            ], 422);
        }

        if ($this->isBlockedByAdminBlock($date, $proposedStart)) {
            return response()->json([
                'message' => '選択された時間枠はブロック設定により予約できません。',
            ], 409);
        }

        $assignedTable = null;
        $notes = (string) ($request->notes ?? '');

        if ($partySize >= 5) {
            $tatamiPair = $this->getTatamiPair();
            if ($tatamiPair->count() < 2 || !$this->isTatamiPairAvailable($date, $proposedStart, $tatamiPair->pluck('id')->all())) {
                return response()->json([
                    'message' => '5〜8名様向けの座敷結合席に空きがありません。別時間をご検討ください。',
                ], 409);
            }

            $assignedTable = $tatamiPair->first();
            $notes = trim($notes === '' ? '座敷結合（A+B）' : $notes . "\n座敷結合（A+B）");
        } else {
            $assignedTable = $this->findAvailableTableForSmallGroup($date, $proposedStart, $partySize, $seatPreference);
            if (!$assignedTable) {
                return response()->json([
                    'message' => '選択された時間帯は満席です。別時間をご検討ください。',
                ], 409);
            }
        }

        $user = $request->user();
        $baseName = $user ? $user->name : $request->name;
        $baseEmail = $user ? $user->email : $request->email;
        $basePhone = $user ? $user->phone : $request->phone;

        $customer = null;
        if ($baseEmail) {
            $customer = Customer::updateOrCreate(
                ['email' => $baseEmail],
                ['name' => $baseName, 'phone' => $basePhone]
            );
        }

        try {
            $reservation = Reservation::create([
                'user_id' => $user?->id,
                'customer_id' => $customer?->id,
                'service_id' => $request->service_id,
                'table_id' => $assignedTable?->id,
                'party_size' => $partySize,
                'seat_preference' => $seatPreference,
                'name' => $baseName,
                'email' => $baseEmail,
                'phone' => $basePhone,
                'date' => $request->date,
                'start_time' => $proposedStart->format('H:i:s'),
                'end_time' => $proposedEnd->format('H:i:s'),
                'status' => 'confirmed',
                'notes' => $notes !== '' ? $notes : null,
                'reservation_code' => strtoupper(uniqid('RSV')),
            ]);
        } catch (QueryException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                return response()->json([
                    'message' => '選択された時間枠は既に他の予約で埋まっています。（DB制約）',
                ], 409);
            }

            Log::error('[予約登録エラー] ' . $e->getMessage(), [
                'date' => $request->date,
                'start_time' => $request->start_time,
                'party_size' => $partySize,
            ]);

            return response()->json([
                'message' => '予約処理中にエラーが発生しました。',
            ], 500);
        }

        $reservation->load(['service', 'table']);

        if ($customer) {
            $customer->recalculateStats();
        }

        try {
            $this->scheduleReservationEmails($reservation, $proposedStart);
        } catch (\Throwable $e) {
            Log::error('[予約メールスケジュール登録エラー] ' . $e->getMessage(), [
                'reservation_id' => $reservation->id ?? null,
            ]);
        }

        try {
            Mail::to($reservation->email)->send(new ReservationConfirmedMail($reservation));

            $adminEmail = env('MAIL_ADMIN_ADDRESS', 'admin@izuura.local');
            Mail::to($adminEmail)->send(new AdminReservationNoticeMail($reservation));
        } catch (\Exception $e) {
            Log::error('[メール送信エラー] ' . $e->getMessage(), [
                'reservation_id' => $reservation->id ?? null,
                'email' => $reservation->email ?? null,
            ]);
        }

        return response()->json([
            'message' => '予約が完了しました（確認メールを送信しました）。',
            'reservation' => $reservation,
        ], 201);
    }

    public function index()
    {
        $reservations = Reservation::with(['service', 'table'])
            ->orderBy('date', 'desc')
            ->get()
            ->map(fn ($r) => [
                'id' => $r->id,
                'name' => $r->name,
                'service_name' => $r->service->name ?? '未設定',
                'table_name' => $r->table->name ?? '未割当',
                'party_size' => $r->party_size,
                'date' => $r->date,
                'start_time' => $r->start_time,
                'status' => $r->status ?? '予約中',
            ]);

        return response()->json($reservations);
    }

    public function destroy($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => '予約が見つかりません。'], 404);
        }

        $reservation->delete();

        return response()->json(['message' => '削除しました。'], 200);
    }

    protected function resolveOpenCloseByBusinessHour(Carbon $date): array
    {
        $year = (int) $date->year;
        $month = (int) $date->month;

        if (BusinessHour::where('year', $year)->where('month', $month)->count() === 0) {
            BusinessHour::seedDefaultForMonth($year, $month);
        }

        $week = BusinessHour::getWeekOfMonth($date);
        $dayJa = ['日', '月', '火', '水', '木', '金', '土'][$date->dayOfWeek];

        $bh = BusinessHour::where('year', $year)
            ->where('month', $month)
            ->where('week_of_month', $week)
            ->where('day_of_week', $dayJa)
            ->first();

        if (!$bh) {
            return [null, null, '営業時間が未設定です。'];
        }

        if ($bh->is_closed) {
            return [null, null, '本日は休業日です。'];
        }

        $openStr = BusinessHour::normalizeTimeToHi($bh->open_time);
        $closeStr = BusinessHour::normalizeTimeToHi($bh->close_time);

        if (!$openStr || !$closeStr) {
            return [null, null, '営業時間が未設定です。'];
        }

        $open = Carbon::parse($date->format('Y-m-d') . ' ' . $openStr);
        $close = Carbon::parse($date->format('Y-m-d') . ' ' . $closeStr);

        if ($close->lte($open)) {
            return [null, null, '営業時間の設定が不正です。'];
        }

        return [$open, $close, null];
    }

    protected function alignToHalfHour(Carbon $dt): Carbon
    {
        $minute = (int) $dt->format('i');
        $mod = $minute % 30;

        if ($mod !== 0) {
            $dt->addMinutes(30 - $mod);
        }

        return $dt->setSecond(0);
    }

    protected function isBlockedByAdminBlock(Carbon $date, Carbon $start): bool
    {
        $startT = $start->format('H:i:s');

        return AdminBlock::where('date', $date->format('Y-m-d'))
            ->where('start_time', '<=', $startT)
            ->where('end_time', '>=', $startT)
            ->exists();
    }

    protected function getOccupiedTableIds(Carbon $date, Carbon $start): array
    {
        $startT = $start->format('H:i:s');

        return Reservation::query()
            ->where('date', $date->format('Y-m-d'))
            ->where('status', 'confirmed')
            ->whereNotNull('table_id')
            ->where('start_time', '<=', $startT)
            ->where('end_time', '>=', $startT)
            ->pluck('table_id')
            ->all();
    }

    protected function findAvailableTableForSmallGroup(
        Carbon $date,
        Carbon $start,
        int $partySize,
        ?string $seatPreference
    ): ?Table {
        $occupied = $this->getOccupiedTableIds($date, $start);

        $query = Table::query()
            ->active()
            ->where('capacity', '>=', $partySize)
            ->whereNotIn('id', $occupied)
            ->orderBy('sort_order');

        if ($seatPreference) {
            $preferred = (clone $query)->where('type', $seatPreference)->first();
            if ($preferred) {
                return $preferred;
            }
        }

        return $query->first();
    }

    protected function getTatamiPair()
    {
        return Table::query()
            ->active()
            ->where('combine_group', 1)
            ->orderBy('sort_order')
            ->get();
    }

    protected function isTatamiPairAvailable(Carbon $date, Carbon $start, array $tatamiIds): bool
    {
        if (count($tatamiIds) < 2) {
            return false;
        }

        $occupied = $this->getOccupiedTableIds($date, $start);

        return collect($tatamiIds)->every(fn ($id) => !in_array($id, $occupied, true));
    }

    protected function scheduleReservationEmails(Reservation $reservation, Carbon $startDateTime): void
    {
        $email = $reservation->email;
        $userId = $reservation->user_id;

        $this->createScheduleEntry(
            $reservation,
            $userId,
            $email,
            'reservation_reminder_2days',
            $startDateTime->copy()->subDays(2)
        );

        $this->createScheduleEntry(
            $reservation,
            $userId,
            $email,
            'reservation_reminder_1day',
            $startDateTime->copy()->subDay()
        );

        $this->createScheduleEntry(
            $reservation,
            $userId,
            $email,
            'reservation_thanks_3days',
            $startDateTime->copy()->addDays(3)
        );

        $this->createScheduleEntry(
            $reservation,
            $userId,
            $email,
            'reservation_thanks_1month',
            $startDateTime->copy()->addMonth()
        );
    }

    protected function createScheduleEntry(
        Reservation $reservation,
        ?int $userId,
        string $email,
        string $type,
        Carbon $sendAt
    ): void {
        if ($sendAt->lte(now())) {
            return;
        }

        ScheduledEmail::updateOrCreate(
            [
                'type' => $type,
                'related_type' => Reservation::class,
                'related_id' => $reservation->id,
            ],
            [
                'user_id' => $userId,
                'email' => $email,
                'send_at' => $sendAt,
                'status' => 'pending',
            ]
        );
    }
}
