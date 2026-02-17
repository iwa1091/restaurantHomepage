<?php

namespace App\Mail;

use App\Models\BanquetInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminBanquetDepositPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public BanquetInquiry $inquiry)
    {
    }

    public function build()
    {
        return $this->subject('【すし割烹 いづ浦】宴会デポジットの入金がありました')
            ->view('emails.banquet.admin-deposit-paid');
    }
}
