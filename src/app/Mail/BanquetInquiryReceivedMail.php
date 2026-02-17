<?php

namespace App\Mail;

use App\Models\BanquetInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BanquetInquiryReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public BanquetInquiry $inquiry)
    {
    }

    public function build()
    {
        return $this->subject('【すし割烹 いづ浦】宴会お問い合わせ受付のお知らせ')
            ->view('emails.banquet.inquiry-received');
    }
}
