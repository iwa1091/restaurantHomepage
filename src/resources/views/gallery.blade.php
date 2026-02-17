@extends('layout.app')

@section('title', 'ギャラリー | すし割烹 いづ浦')

@section('styles')
    @vite(['resources/css/pages/gallery/gallery.css'])
@endsection

@section('content')
<div class="gallery-page-container">

    {{-- =============================
         ① ヒーローバナー
    ============================== --}}
    <section class="gallery-hero">
        <img src="{{ asset('img/sashimi.webp') }}"
             alt="九十九里直送の刺身盛り合わせ"
             class="gallery-hero-img">
        <div class="gallery-hero-overlay">
            <h1 class="gallery-hero-title">九十九里の恵みを、一皿に。</h1>
            <p class="gallery-hero-sub">料理と空間のギャラリー</p>
        </div>
    </section>

    {{-- =============================
         ② 料理ギャラリー
    ============================== --}}
    <section class="gallery-section">
        <div class="section-header">
            <h2 class="section-heading">四季折々の味わい</h2>
            <p class="section-lead">片貝漁港直送の鮮魚を中心に、熟練の技で仕上げる逸品をご覧ください。</p>
        </div>

        <div class="food-grid">
            {{-- メイン（大サイズ）：刺身盛り合わせ --}}
            <div class="food-card food-card-main">
                <img src="{{ asset('img/sashimi.webp') }}"
                     alt="刺身盛り合わせ" class="food-card-img">
                <div class="food-card-caption">
                    <h3>刺身盛り合わせ</h3>
                    <p>片貝漁港直送の鮮魚を贅沢に盛り合わせ。その日の仕入れに合わせた、旬の味覚をお届けします。</p>
                </div>
            </div>

            {{-- サブ4枚 --}}
            <div class="food-card">
                <img src="{{ asset('img/agemono.webp') }}"
                     alt="揚げ物盛り合わせ" class="food-card-img">
                <div class="food-card-caption">
                    <h3>揚げ物盛り合わせ</h3>
                    <p>半身唐揚げや若鶏の唐揚げなど、ボリューム満点の揚げ物をお楽しみいただけます。</p>
                </div>
            </div>

            <div class="food-card">
                <img src="{{ asset('img/gallery-fugu.webp') }}"
                     alt="ふぐ料理" class="food-card-img">
                <div class="food-card-caption">
                    <h3>ふぐ料理</h3>
                    <p>九十九里沖の天然フグを、唐揚げ・一夜干し・鍋料理など多彩な調理法でお届けします。</p>
                </div>
            </div>

            <div class="food-card">
                <img src="{{ asset('img/gallery-sashimi.webp') }}"
                     alt="旬の握り" class="food-card-img">
                <div class="food-card-caption">
                    <h3>旬の握り</h3>
                    <p>職人が一貫一貫丁寧に握る、旬のネタを活かした握り寿司。</p>
                </div>
            </div>

            <div class="food-card">
                <img src="{{ asset('img/gallery-sukiyaki.webp') }}"
                     alt="すき焼き" class="food-card-img">
                <div class="food-card-caption">
                    <h3>すき焼き</h3>
                    <p>厳選した和牛と旬の野菜を使用。素材の旨みを最大限に引き出す味付けです。</p>
                </div>
            </div>
        </div>
    </section>

    {{-- =============================
         ③ 満足度スタッツ（料理直後に配置）
    ============================== --}}
    <section class="section-stats">
        <div class="stats-container">
            <h2 class="section-title">
                Google口コミ お客様満足度
            </h2>

            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">45年</div>
                    <div class="stat-label">料理人歴</div>
                </div>

                <div class="stat-item">
                    <div class="stat-value">30名</div>
                    <div class="stat-label">最大宴会収容人数</div>
                </div>

                <div class="stat-item">
                    <div class="stat-value">★4.9</div>
                    <div class="stat-label">平均評価</div>
                </div>
            </div>
        </div>
    </section>

    {{-- =============================
         ④ お客様の声（スタッツ直後で裏付け）
    ============================== --}}
    <section class="gallery-section">
        <div class="section-header">
            <h2 class="section-heading">お客様の声</h2>
            <p class="section-lead review-note">
                ※Googleマップなどでいただいたお声を要約して掲載しています。
            </p>
        </div>

        <div class="grid-reviews">
            @foreach($reviews as $review)
                <div class="card card-review">
                    <div class="review-header">
                        <div>
                            <div class="review-name-age">
                                {{ $review->name }}
                                @if (!empty($review->age))
                                    <span class="review-age">({{ $review->age }})</span>
                                @endif
                            </div>

                            <div class="review-rating">
                                @for ($i = 0; $i < $review->rating; $i++)
                                    <img src="{{ asset('img/star.png') }}"
                                         class="star"
                                         alt="★">
                                @endfor
                            </div>
                        </div>

                        <img src="{{ asset('img/quote.png') }}"
                             alt="Quote"
                             class="quote">
                    </div>

                    <p class="review-comment">{{ $review->comment }}</p>

                    <div class="review-details">
                        <span>{{ $review->service }}</span>
                        <span>{{ $review->date }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- =============================
         ⑤ 空間ギャラリー
    ============================== --}}
    <section class="gallery-section">
        <div class="section-header">
            <h2 class="section-heading">くつろぎの空間</h2>
            <p class="section-lead">カウンター席からお座敷まで、さまざまなシーンに対応いたします。</p>
        </div>

        <div class="space-grid">
            <div class="space-card">
                <img src="{{ asset('img/gaikan.webp') }}"
                     alt="外観" class="space-card-img">
                <div class="space-card-caption">
                    <h3>外観</h3>
                    <p>黒を基調とした落ち着いた佇まい。駐車場完備でお車でも安心です。</p>
                </div>
            </div>

            <div class="space-card">
                <img src="{{ asset('img/tennnai.webp') }}"
                     alt="カウンター・テーブル席" class="space-card-img">
                <div class="space-card-caption">
                    <h3>カウンター・テーブル席</h3>
                    <p>目の前で職人の技を楽しめるカウンター席と、ゆったりとしたテーブル席。</p>
                </div>
            </div>

            <div class="space-card">
                <img src="{{ asset('img/ozasiki.webp') }}"
                     alt="宴会場（お座敷）" class="space-card-img">
                <div class="space-card-caption">
                    <h3>宴会場（お座敷）</h3>
                    <p>最大30名様対応の広々としたお座敷。カラオケ完備で、法事・宴会・会食に最適です。</p>
                </div>
            </div>
        </div>
    </section>

    {{-- =============================
         ⑥ CTA（予約導線 — 最後に背中を押す）
    ============================== --}}
    <section class="gallery-cta">
        <div class="gallery-cta-inner">
            <h2 class="gallery-cta-title">ご来店をお待ちしております</h2>
            <p class="gallery-cta-text">
                お料理や空間が気に入りましたら、ぜひお気軽にお問い合わせください。
            </p>
            <div class="gallery-cta-buttons">
                <a href="{{ route('contact.form') }}" class="cta-btn cta-btn-primary">
                    ご予約・お問い合わせ
                </a>
                <a href="tel:080-9704-9500" class="cta-btn cta-btn-outline">
                    お電話でのご予約
                </a>
            </div>
            <p class="gallery-cta-phone">TEL: 080-9704-9500（火〜日 17:00〜22:00）</p>
        </div>
    </section>

</div>
@endsection
