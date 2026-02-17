<p>宴会デポジットの入金がありました。</p>
<p>問い合わせID: {{ $inquiry->id }}</p>
<p>氏名: {{ $inquiry->name }}</p>
<p>入金金額: ¥{{ number_format((int) $inquiry->deposit_amount) }}</p>
