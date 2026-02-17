<?php

namespace App\Mail;

use App\Models\BanquetInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BanquetCanceledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public BanquetInquiry $inquiry, public int $refundAmount = 0)
    {
    }

    public function build()
    {
        return $this->subject('【すし割烹 いづ浦】宴会予約キャンセルのお知らせ')
            ->view('emails.banquet.canceled');
    }
}
