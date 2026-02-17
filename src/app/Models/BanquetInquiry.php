<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BanquetInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'name',
        'email',
        'phone',
        'party_size',
        'preferred_date',
        'preferred_time',
        'budget_per_person',
        'course_preference',
        'notes',
        'status',
        'deposit_amount',
        'stripe_session_id',
        'stripe_payment_id',
        'deposit_status',
        'deposit_paid_at',
        'cancel_reason',
    ];

    protected $appends = [
        'status_label',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'deposit_paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => '問い合わせ受付',
            'confirmed_by_store' => '店舗確認済',
            'deposit_sent' => 'デポジット請求送信済',
            'deposit_paid' => 'デポジット入金済',
            'completed' => '宴会実施済',
            'canceled' => 'キャンセル',
            default => '未設定',
        };
    }

    public function calculateDeposit(): int
    {
        return max(0, (int) $this->party_size) * 1000;
    }
}
