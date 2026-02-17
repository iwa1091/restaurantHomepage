<?php

namespace App\Http\Controllers;

use App\Mail\AdminBanquetInquiryNoticeMail;
use App\Mail\BanquetInquiryReceivedMail;
use App\Models\BanquetInquiry;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class BanquetInquiryController extends Controller
{
    public function form()
    {
        return Inertia::render('Banquet/BanquetInquiryForm');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'party_size' => ['required', 'integer', 'min:10'],
            'preferred_date' => ['required', 'date', 'after:' . now()->addDays(6)->toDateString()],
            'preferred_time' => ['nullable', 'string', 'max:50'],
            'budget_per_person' => ['nullable', 'string', 'max:50'],
            'course_preference' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ], [
            'party_size.min' => '宴会予約は10名以上でご入力ください。',
            'preferred_date.after' => 'ご希望日は1週間後以降を選択してください。',
        ]);

        $customer = Customer::updateOrCreate(
            ['email' => $validated['email']],
            ['name' => $validated['name'], 'phone' => $validated['phone']]
        );

        $preferredTime = match ($validated['preferred_time'] ?? null) {
            'ランチ' => '12:00:00',
            'ディナー' => '18:00:00',
            default => null,
        };

        $inquiry = BanquetInquiry::create([
            'user_id' => $request->user()?->id,
            'customer_id' => $customer?->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'party_size' => (int) $validated['party_size'],
            'preferred_date' => $validated['preferred_date'],
            'preferred_time' => $preferredTime,
            'budget_per_person' => preg_replace('/[^0-9]/', '', (string) ($validated['budget_per_person'] ?? '')) ?: null,
            'course_preference' => $validated['course_preference'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
            'deposit_status' => 'pending',
        ]);

        $adminEmail = env('MAIL_ADMIN_ADDRESS', 'admin@izuura.local');
        Mail::to($adminEmail)->send(new AdminBanquetInquiryNoticeMail($inquiry));
        Mail::to($inquiry->email)->send(new BanquetInquiryReceivedMail($inquiry));

        return redirect()->route('banquet.form')->with('success', '宴会のお問い合わせを受け付けました。店舗より折り返しご連絡いたします。');
    }

    public function depositSuccess(Request $request)
    {
        return view('banquet.deposit-success');
    }

    public function depositCancel(Request $request)
    {
        return view('banquet.deposit-cancel');
    }
}
