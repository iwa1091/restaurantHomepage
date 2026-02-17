<?php

namespace App\Mail;

use App\Models\BanquetInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BanquetDepositConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public BanquetInquiry $inquiry)
    {
    }

    public function build()
    {
        return $this->subject('【すし割烹 いづ浦】デポジット入金確認・予約確定のお知らせ')
            ->view('emails.banquet.deposit-confirmed');
    }
}
