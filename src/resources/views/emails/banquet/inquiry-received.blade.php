<p>{{ $inquiry->name }} 様</p>
<p>このたびは、すし割烹 いづ浦へ宴会のお問い合わせをいただきありがとうございます。</p>
<p>内容を確認のうえ、店舗より折り返しご連絡いたします。</p>
<p>ご希望日: {{ optional($inquiry->preferred_date)->format('Y-m-d') }}</p>
<p>人数: {{ $inquiry->party_size }}名</p>
