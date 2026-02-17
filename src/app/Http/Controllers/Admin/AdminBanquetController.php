<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BanquetCanceledMail;
use App\Mail\BanquetDepositRequestMail;
use App\Models\BanquetInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Stripe\Checkout\Session;
use Stripe\Refund;
use Stripe\Stripe;

class AdminBanquetController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $q = $request->query('q');

        $query = BanquetInquiry::query()->orderByDesc('created_at');

        if ($status) {
            $query->where('status', $status);
        }

        if ($q) {
            $query->where(function ($inner) use ($q) {
                $inner->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        return Inertia::render('Admin/BanquetList', [
            'inquiries' => $query->paginate(20)->through(fn (BanquetInquiry $i) => [
                'id' => $i->id,
                'name' => $i->name,
                'email' => $i->email,
                'phone' => $i->phone,
                'party_size' => $i->party_size,
                'preferred_date' => optional($i->preferred_date)->format('Y-m-d'),
                'preferred_time' => $i->preferred_time,
                'status' => $i->status,
                'status_label' => $i->status_label,
                'deposit_amount' => $i->deposit_amount,
                'deposit_status' => $i->deposit_status,
                'created_at' => optional($i->created_at)->format('Y-m-d H:i'),
            ]),
            'filters' => [
                'status' => $status,
                'q' => $q,
            ],
        ]);
    }

    public function show($id)
    {
        $inquiry = BanquetInquiry::findOrFail($id);

        return Inertia::render('Admin/BanquetDetail', [
            'inquiry' => $inquiry,
        ]);
    }

    public function update(Request $request, $id)
    {
        $inquiry = BanquetInquiry::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $inquiry->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $inquiry->notes,
        ]);

        return back()->with('success', '宴会問い合わせを更新しました。');
    }

    public function sendDeposit($id)
    {
        $inquiry = BanquetInquiry::findOrFail($id);

        Stripe::setApiKey(config('services.stripe.secret'));

        $depositAmount = $inquiry->calculateDeposit();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => "宴会デポジット（{$inquiry->party_size}名様分）",
                    ],
                    'unit_amount' => $depositAmount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('banquet.deposit.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('banquet.deposit.cancel'),
            'customer_email' => $inquiry->email,
            'metadata' => [
                'banquet_inquiry_id' => (string) $inquiry->id,
                'customer_id' => (string) ($inquiry->customer_id ?? ''),
            ],
        ]);

        $inquiry->update([
            'deposit_amount' => $depositAmount,
            'stripe_session_id' => $session->id,
            'status' => 'deposit_sent',
            'deposit_status' => 'pending',
        ]);

        Mail::to($inquiry->email)->send(new BanquetDepositRequestMail($inquiry, $session->url));

        return back()->with('success', 'デポジット決済URLを送信しました。');
    }

    public function cancel(Request $request, $id)
    {
        $inquiry = BanquetInquiry::findOrFail($id);

        $validated = $request->validate([
            'cancel_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $inquiry->update([
            'status' => 'canceled',
            'cancel_reason' => $validated['cancel_reason'] ?? null,
        ]);

        Mail::to($inquiry->email)->send(new BanquetCanceledMail($inquiry, 0));

        return back()->with('success', '宴会予約をキャンセルしました。');
    }

    public function refund($id)
    {
        $inquiry = BanquetInquiry::findOrFail($id);

        if (!$inquiry->stripe_payment_id || !$inquiry->deposit_amount) {
            return back()->withErrors(['refund' => '返金可能な決済情報がありません。']);
        }

        $daysBefore = now()->startOfDay()->diffInDays($inquiry->preferred_date->copy()->startOfDay(), false);
        $rate = $daysBefore >= 7 ? 1.0 : ($daysBefore >= 3 ? 0.5 : 0.0);
        $refundAmount = (int) floor((int) $inquiry->deposit_amount * $rate);

        if ($refundAmount > 0) {
            Stripe::setApiKey(config('services.stripe.secret'));
            Refund::create([
                'payment_intent' => $inquiry->stripe_payment_id,
                'amount' => $refundAmount,
            ]);
        }

        $inquiry->update([
            'status' => 'canceled',
            'deposit_status' => $refundAmount === (int) $inquiry->deposit_amount ? 'refunded' : 'partially_refunded',
        ]);

        Mail::to($inquiry->email)->send(new BanquetCanceledMail($inquiry, $refundAmount));

        return back()->with('success', '返金処理を実行しました。');
    }
}
