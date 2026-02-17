<p>宴会の問い合わせが入りました。</p>
<p>氏名: {{ $inquiry->name }}</p>
<p>メール: {{ $inquiry->email }}</p>
<p>電話: {{ $inquiry->phone }}</p>
<p>人数: {{ $inquiry->party_size }}名</p>
<p>希望日: {{ optional($inquiry->preferred_date)->format('Y-m-d') }}</p>
