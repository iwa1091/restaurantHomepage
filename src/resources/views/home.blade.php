@extends('layout.app')

@section('title', 'すし割烹 いづ浦 | 九十九里の海の幸')

@section('styles')
    @vite(['resources/css/pages/home/home.css'])
@endsection

@section('content')
    <div class="home-page-container">
        {{-- ヒーローセクション --}}
        <section class="hero-section">
            <div class="hero-image-overlay"></div>
            <div class="hero-content-container">
                <div class="hero-text-wrapper">
                    <div class="hero-text-group">
                        <p class="hero-label">すし割烹 いづ浦</p>
                        <h1 class="hero-title">
                            <span class="nowrap-text">九十九里の</span><br />
                            <span class="nowrap-text"><span class="highlight-text">海の幸</span>を極めた</span><br />
                            <span class="nowrap-text">老舗の味</span>
                        </h1>
                        <p class="hero-subtitle">
                            料理人歴45年の店主が腕を振るう<br />
                            こだわりの料理店
                        </p>
                    </div>
                    <div class="hero-buttons-wrapper">
                        <a href="{{ route('menu_price') }}" class="button button-primary">
                            <span class="nowrap-text">コース・お席予約</span>
                        </a>
                        <a href="#menu" class="button button-secondary">
                            <span class="nowrap-text">メニューを見る</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- こだわりセクション --}}
        <section class="features-section">
            <div class="content-container">
                <div class="section-header">
                    <h2 class="section-title">いづ浦のこだわり</h2>
                    <p class="section-description">
                        地元・九十九里の食材と熟練の技で、心に残る一皿をお届けします
                    </p>
                </div>

                <div class="features-grid">
                    @php
                        $features = [
                            [
                                'icon' => 'fa-solid fa-utensils',
                                'title' => '熟練の技',
                                'description' => "料理人歴45年の店主が、\n旬の食材を生かした美味しさを\n一皿一皿丁寧にお届けします"
                            ],
                            [
                                'icon' => 'fa-solid fa-fish',
                                'title' => '地元の新鮮素材',
                                'description' => "片貝漁港で獲れる新鮮なフグをはじめ、\n九十九里の海の幸を\nふんだんに使用しています"
                            ],
                            [
                                'icon' => 'fa-solid fa-champagne-glasses',
                                'title' => '宴会・法事に',
                                'description' => "最大30名様対応の宴会室を完備。\nカラオケ付きで、\n法事・宴会・会食に最適です"
                            ]
                        ];
                    @endphp
                    @foreach($features as $feature)
                        <div class="feature-card">
                            <div class="card-content">
                                <div class="feature-icon-wrapper">
                                    <i class="{{ $feature['icon'] }} feature-fa-icon"></i>
                                </div>
                                <h3 class="feature-title">{{ $feature['title'] }}</h3>
                                <p class="feature-description">{!! nl2br(e($feature['description'])) !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- おすすめ料理セクション --}}
        <section class="specialty-section">
            <div class="content-container">
                <div class="section-header">
                    <h2 class="section-title">当店のおすすめ</h2>
                    <p class="section-description">
                        九十九里の新鮮な食材を、熟練の技で仕上げた自慢の料理
                    </p>
                </div>

                <div class="specialty-grid">
                    <div class="specialty-card">
                        <div class="specialty-card-inner">
                            <div class="specialty-image-area">
                                <img src="{{ asset('img/sashimi.webp') }}" alt="刺身盛り合わせ" class="specialty-image">
                            </div>
                            <div class="specialty-icon-area">
                                <i class="fa-solid fa-fish specialty-fa-icon"></i>
                            </div>
                            <h3 class="specialty-name">ふぐ料理</h3>
                            <p class="specialty-description">
                                九十九里沖で水揚げされる天然のショウサイフグ・トラフグを、唐揚げ・一夜干し・鍋料理など多彩な調理法でお楽しみいただけます。「高級感」と「地元らしさ」を兼ね備えた逸品です。
                            </p>
                            <div class="specialty-price">ふぐから揚げ 1,500円〜</div>
                        </div>
                    </div>

                    <div class="specialty-card">
                        <div class="specialty-card-inner">
                            <div class="specialty-image-area">
                                <img src="{{ asset('img/agemono.webp') }}" alt="揚げ物盛り合わせ" class="specialty-image">
                            </div>
                            <div class="specialty-icon-area">
                                <i class="fa-solid fa-fire specialty-fa-icon"></i>
                            </div>
                            <h3 class="specialty-name">すき焼き</h3>
                            <p class="specialty-description">
                                厳選した和牛と旬の野菜を使用した、当店自慢のすき焼き。素材の旨みを最大限に引き出す味付けで、特別なひとときをお過ごしください。
                            </p>
                            <div class="specialty-price">お問い合わせください</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 店内のご案内セクション --}}
        <section class="venue-section">
            <div class="content-container">
                <div class="section-header">
                    <h2 class="section-title">店内のご案内</h2>
                    <p class="section-description">
                        落ち着いた和の空間で、ゆったりとお食事をお楽しみいただけます
                    </p>
                </div>

                <div class="venue-grid">
                    {{-- 外観（大きく表示） --}}
                    <div class="venue-card venue-card-main">
                        <div class="venue-image-wrapper">
                            <img src="{{ asset('img/gaikan.webp') }}" alt="すし割烹 いづ浦 外観" class="venue-image">
                        </div>
                        <div class="venue-card-body">
                            <h3 class="venue-card-title">外観</h3>
                            <p class="venue-card-text">
                                九十九里町片貝の黒い和風建築が目印。<br>
                                広い駐車場を完備しております。
                            </p>
                        </div>
                    </div>

                    {{-- 店内 --}}
                    <div class="venue-card">
                        <div class="venue-image-wrapper">
                            <img src="{{ asset('img/tennnai.webp') }}" alt="店内の様子" class="venue-image">
                        </div>
                        <div class="venue-card-body">
                            <h3 class="venue-card-title">店内</h3>
                            <p class="venue-card-text">
                                カウンター席とテーブル席をご用意。<br>
                                目の前で調理する臨場感をお楽しみください。
                            </p>
                        </div>
                    </div>

                    {{-- お座敷・宴会場 --}}
                    <div class="venue-card">
                        <div class="venue-image-wrapper">
                            <img src="{{ asset('img/ozasiki.webp') }}" alt="お座敷・宴会場" class="venue-image">
                        </div>
                        <div class="venue-card-body">
                            <h3 class="venue-card-title">お座敷・宴会場</h3>
                            <p class="venue-card-text">
                                最大30名様対応の広い畳座敷。<br>
                                カラオケ完備で法事・宴会・会食に最適です。
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- お食事メニュー・ドリンクメニューセクション --}}
        <section id="menu" class="menu-list-section">
            <div class="content-container">
                <div class="section-header">
                    <h2 class="section-title">お食事メニュー一覧</h2>
                    <p class="section-description">
                        九十九里の新鮮な魚介を使った寿司や海鮮丼、地元ならではの一品料理を多数ご用意しています。
                    </p>
                    <p class="menu-list-notice">※価格は税別です。メニューは改定・変更される場合があります。</p>
                </div>

                <div class="menu-list-grid">

                    {{-- 寿司・丼もの・刺身 --}}
                    <div class="menu-list-category">
                        <h3 class="menu-list-category-title">
                            <i class="fa-solid fa-fish menu-list-icon"></i>
                            寿司・丼もの・刺身
                        </h3>
                        <ul class="menu-list-items">
                            <li class="menu-list-item"><span>にぎり鮨</span><span class="menu-list-price">2,400円</span></li>
                            <li class="menu-list-item"><span>海鮮丼</span><span class="menu-list-price">2,400円</span></li>
                            <li class="menu-list-item"><span>鉄火丼</span><span class="menu-list-price">2,500円</span></li>
                            <li class="menu-list-item"><span>ネギトロ巻き（1本）</span><span class="menu-list-price">1,000円</span></li>
                            <li class="menu-list-item"><span>鉄火巻（1本）</span><span class="menu-list-price">800円</span></li>
                            <li class="menu-list-item"><span>のり巻（1本）</span><span class="menu-list-price">300円</span></li>
                            <li class="menu-list-item"><span>梅しそ巻（1本）</span><span class="menu-list-price">300円</span></li>
                            <li class="menu-list-item"><span>お新香巻（1本）</span><span class="menu-list-price">300円</span></li>
                            <li class="menu-list-item"><span>カッパ巻（1本）</span><span class="menu-list-price">300円</span></li>
                            <li class="menu-list-item"><span>納豆巻（1本）</span><span class="menu-list-price">300円</span></li>
                            <li class="menu-list-item"><span>刺身盛合せ</span><span class="menu-list-price">3,500円〜</span></li>
                            <li class="menu-list-item"><span>平貝刺身</span><span class="menu-list-price">2,400円</span></li>
                            <li class="menu-list-item"><span>ミル貝刺身</span><span class="menu-list-price">2,000円</span></li>
                            <li class="menu-list-item"><span>白身魚カルパッチョ</span><span class="menu-list-price">1,500円</span></li>
                        </ul>
                    </div>

                    {{-- 魚・貝料理 --}}
                    <div class="menu-list-category">
                        <h3 class="menu-list-category-title">
                            <i class="fa-solid fa-shrimp menu-list-icon"></i>
                            魚・貝料理
                        </h3>
                        <ul class="menu-list-items">
                            <li class="menu-list-item"><span>海鮮サラダ</span><span class="menu-list-price">1,450円</span></li>
                            <li class="menu-list-item"><span>カキ酢</span><span class="menu-list-price">1,600円</span></li>
                            <li class="menu-list-item"><span>煮アワビ</span><span class="menu-list-price">1,800円</span></li>
                            <li class="menu-list-item"><span>うなぎとごぼうの卵とじ</span><span class="menu-list-price">2,350円</span></li>
                            <li class="menu-list-item"><span>金目鯛煮つけ</span><span class="menu-list-price">2,850円</span></li>
                        </ul>
                    </div>

                    {{-- 焼き物 --}}
                    <div class="menu-list-category">
                        <h3 class="menu-list-category-title">
                            <i class="fa-solid fa-fire-burner menu-list-icon"></i>
                            焼き物
                        </h3>
                        <ul class="menu-list-items">
                            <li class="menu-list-item"><span>きのことホタテのグラタン</span><span class="menu-list-price">950円</span></li>
                            <li class="menu-list-item"><span>サバみそ煮</span><span class="menu-list-price">850円</span></li>
                            <li class="menu-list-item"><span>鰆の西京焼き</span><span class="menu-list-price">1,600円</span></li>
                            <li class="menu-list-item"><span>ホッキ浜焼き</span><span class="menu-list-price">900円</span></li>
                            <li class="menu-list-item"><span>アワビバター焼き</span><span class="menu-list-price">2,200円</span></li>
                            <li class="menu-list-item"><span>ホタテバター焼き</span><span class="menu-list-price">1,800円</span></li>
                            <li class="menu-list-item"><span>サーモンバター焼き</span><span class="menu-list-price">1,200円</span></li>
                        </ul>
                    </div>

                    {{-- ふぐ料理 --}}
                    <div class="menu-list-category">
                        <h3 class="menu-list-category-title">
                            <i class="fa-solid fa-fish menu-list-icon"></i>
                            ふぐ料理
                        </h3>
                        <ul class="menu-list-items">
                            <li class="menu-list-item"><span>ふぐ白子焼き</span><span class="menu-list-price">2,000円〜3,500円</span></li>
                            <li class="menu-list-item"><span>ふぐ一夜干</span><span class="menu-list-price">800円〜1,200円</span></li>
                            <li class="menu-list-item"><span>ふぐから揚げ</span><span class="menu-list-price">1,500円</span></li>
                            <li class="menu-list-item"><span>ふぐフライ</span><span class="menu-list-price">1,500円</span></li>
                            <li class="menu-list-item"><span>ふぐ白子と玉ねぎの卵とじ</span><span class="menu-list-price">1,800円</span></li>
                            <li class="menu-list-item"><span>ひれ酒</span><span class="menu-list-price">1,000円</span></li>
                        </ul>
                    </div>

                    {{-- 肉料理 --}}
                    <div class="menu-list-category">
                        <h3 class="menu-list-category-title">
                            <i class="fa-solid fa-drumstick-bite menu-list-icon"></i>
                            肉料理
                        </h3>
                        <ul class="menu-list-items">
                            <li class="menu-list-item"><span>鳥の半身揚げ</span><span class="menu-list-price">1,600円</span></li>
                            <li class="menu-list-item"><span>鳥のから揚げ</span><span class="menu-list-price">1,000円</span></li>
                            <li class="menu-list-item"><span>和牛ローストビーフ</span><span class="menu-list-price">2,500円</span></li>
                            <li class="menu-list-item"><span>和牛ステーキ</span><span class="menu-list-price">3,500円</span></li>
                            <li class="menu-list-item"><span>サイコロステーキ</span><span class="menu-list-price">2,500円</span></li>
                            <li class="menu-list-item"><span>煮込みハンバーグ</span><span class="menu-list-price">1,000円</span></li>
                            <li class="menu-list-item"><span>牛肉ときのこのほう楽焼</span><span class="menu-list-price">2,000円</span></li>
                            <li class="menu-list-item"><span>トンカツ</span><span class="menu-list-price">2,000円</span></li>
                            <li class="menu-list-item"><span>角煮</span><span class="menu-list-price">1,000円</span></li>
                        </ul>
                    </div>

                    {{-- その他おすすめ一品料理 --}}
                    <div class="menu-list-category">
                        <h3 class="menu-list-category-title">
                            <i class="fa-solid fa-utensils menu-list-icon"></i>
                            その他おすすめ一品料理
                        </h3>
                        <ul class="menu-list-items">
                            <li class="menu-list-item"><span>エシャレット</span><span class="menu-list-price">550円</span></li>
                            <li class="menu-list-item"><span>里芋の揚げだし</span><span class="menu-list-price">600円</span></li>
                            <li class="menu-list-item"><span>ギンナン</span><span class="menu-list-price">500円</span></li>
                            <li class="menu-list-item"><span>いちじくの香州煮</span><span class="menu-list-price">500円</span></li>
                            <li class="menu-list-item"><span>オムレツ</span><span class="menu-list-price">700円</span></li>
                            <li class="menu-list-item"><span>塩ラーメン</span><span class="menu-list-price">1,200円</span></li>
                        </ul>
                    </div>

                </div>

                {{-- ドリンクメニュー --}}
                <div class="section-header menu-list-drink-header">
                    <h2 class="section-title">ドリンクメニュー</h2>
                    <p class="section-description">
                        地元の魚介と相性抜群の日本酒や焼酎、ワインを幅広く取り揃えています。
                    </p>
                </div>

                <div class="menu-list-grid">

                    {{-- ビール・ソフトドリンク --}}
                    <div class="menu-list-category">
                        <h3 class="menu-list-category-title">
                            <i class="fa-solid fa-beer-mug-empty menu-list-icon"></i>
                            ビール・ソフトドリンク
                        </h3>
                        <ul class="menu-list-items">
                            <li class="menu-list-item"><span>生ビール</span><span class="menu-list-price">600円</span></li>
                            <li class="menu-list-item"><span>瓶ビール</span><span class="menu-list-price">700円</span></li>
                            <li class="menu-list-item"><span>コカ・コーラ</span><span class="menu-list-price">350円</span></li>
                            <li class="menu-list-item"><span>オレンジジュース</span><span class="menu-list-price">350円</span></li>
                            <li class="menu-list-item"><span>ジンジャーエール</span><span class="menu-list-price">350円</span></li>
                            <li class="menu-list-item"><span>烏龍茶</span><span class="menu-list-price">300円</span></li>
                        </ul>
                    </div>

                    {{-- 日本酒・冷酒 --}}
                    <div class="menu-list-category">
                        <h3 class="menu-list-category-title">
                            <i class="fa-solid fa-sake menu-list-icon"></i>
                            日本酒・冷酒
                        </h3>
                        <ul class="menu-list-items">
                            <li class="menu-list-item"><span>腰古井（1合）</span><span class="menu-list-price">500円</span></li>
                            <li class="menu-list-item"><span>腰古井（冷酒）</span><span class="menu-list-price">900円</span></li>
                            <li class="menu-list-item"><span>八海山（冷酒）</span><span class="menu-list-price">1,950円</span></li>
                            <li class="menu-list-item"><span>八海山（ボトル）</span><span class="menu-list-price">3,500円</span></li>
                        </ul>
                    </div>

                    {{-- 焼酎ボトル --}}
                    <div class="menu-list-category">
                        <h3 class="menu-list-category-title">
                            <i class="fa-solid fa-bottle-droplet menu-list-icon"></i>
                            焼酎ボトル
                        </h3>
                        <ul class="menu-list-items">
                            <li class="menu-list-item"><span>海</span><span class="menu-list-price">3,800円</span></li>
                            <li class="menu-list-item"><span>山ねこ</span><span class="menu-list-price">3,500円</span></li>
                            <li class="menu-list-item"><span>山ざる</span><span class="menu-list-price">3,500円</span></li>
                            <li class="menu-list-item"><span>焼酎の水割り</span><span class="menu-list-price">600円</span></li>
                        </ul>
                    </div>

                    {{-- ウイスキー・ハイボール --}}
                    <div class="menu-list-category">
                        <h3 class="menu-list-category-title">
                            <i class="fa-solid fa-whiskey-glass menu-list-icon"></i>
                            ウイスキー・ハイボール
                        </h3>
                        <ul class="menu-list-items">
                            <li class="menu-list-item"><span>角ボトル</span><span class="menu-list-price">3,800円</span></li>
                            <li class="menu-list-item"><span>山崎ハイボール</span><span class="menu-list-price">1,200円</span></li>
                            <li class="menu-list-item"><span>白州ハイボール</span><span class="menu-list-price">1,500円</span></li>
                            <li class="menu-list-item"><span>ハイボール</span><span class="menu-list-price">450円</span></li>
                        </ul>
                    </div>

                    {{-- サワー --}}
                    <div class="menu-list-category">
                        <h3 class="menu-list-category-title">
                            <i class="fa-solid fa-lemon menu-list-icon"></i>
                            サワー
                        </h3>
                        <ul class="menu-list-items">
                            <li class="menu-list-item"><span>レモンサワー</span><span class="menu-list-price">600円</span></li>
                            <li class="menu-list-item"><span>プレーンサワー</span><span class="menu-list-price">550円</span></li>
                        </ul>
                    </div>

                    {{-- ワイン --}}
                    <div class="menu-list-category">
                        <h3 class="menu-list-category-title">
                            <i class="fa-solid fa-wine-glass menu-list-icon"></i>
                            ワイン
                        </h3>
                        <ul class="menu-list-items">
                            <li class="menu-list-item"><span>赤ワイン（ボトル）</span><span class="menu-list-price">3,500円</span></li>
                            <li class="menu-list-item"><span>赤ワイン（ハーフ）</span><span class="menu-list-price">1,500円</span></li>
                            <li class="menu-list-item"><span>白ワイン（ボトル）</span><span class="menu-list-price">3,500円</span></li>
                            <li class="menu-list-item"><span>白ワイン（ハーフ）</span><span class="menu-list-price">1,500円</span></li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>

        {{-- 店舗情報セクション --}}
        <section class="info-section">
            <div class="content-container">
                <div class="section-header">
                    <h2 class="section-title">店舗情報</h2>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">営業時間</span>
                        <span class="info-value">17:00〜22:00（ラストオーダー）</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">定休日</span>
                        <span class="info-value">月曜日</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">住所</span>
                        <span class="info-value">千葉県山武郡九十九里町片貝4772</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">電話</span>
                        <span class="info-value">080-9704-9500</span>
                    </div>
                    <div class="info-note">
                        ※営業時間外の日中の法事・宴会も30名まで予約で対応可能です（要予約制）
                    </div>
                </div>
            </div>
        </section>

        {{-- CTAセクション --}}
        <section class="cta-section">
            <div class="content-container">
                <h2 class="cta-title">法事・宴会のご予約もお気軽にどうぞ</h2>
                <p class="cta-description">
                    九十九里の海の幸を味わいたい方や、大切な集まりで上質な料理を楽しみたい方にぴったりのお店です。
                </p>
                <a href="{{ route('contact.form') }}" class="button white-button">ご予約・お問い合わせ</a>
            </div>
        </section>
    </div>
@endsection
