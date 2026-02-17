<p>{{ $inquiry->name }} 様</p>
<p>ご予約確定のため、デポジットのお支払いをお願いいたします。</p>
<p>デポジット金額: ¥{{ number_format((int) $inquiry->deposit_amount) }}</p>
<p><a href="{{ $paymentUrl }}">デポジットを支払う</a></p>
<p>デポジットは当日のお会計から差し引かれます。</p>
