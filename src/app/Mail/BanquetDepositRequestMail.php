<?php

namespace App\Mail;

use App\Models\BanquetInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BanquetDepositRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public BanquetInquiry $inquiry, public string $paymentUrl)
    {
    }

    public function build()
    {
        return $this->subject('【すし割烹 いづ浦】デポジットお支払いのお願い')
            ->view('emails.banquet.deposit-request');
    }
}
