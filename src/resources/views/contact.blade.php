@extends('layout.app')

@section('title', 'ご予約・お問い合わせ')

@section('styles')
    @vite(['resources/css/pages/contact/contact.css'])
@endsection

@section('content')
<div class="contact-page">
    <div class="contact-container">

        <!-- ============================
             ページヘッダー
        ============================ -->
        <div class="page-header">
            <h1>ご予約・お問い合わせ</h1>
            <p>
                ご予約・宴会のご相談など、お気軽にお問い合わせください。<br>
                お電話でも承っております。
            </p>
        </div>

        <div class="contact-layout">

            <!-- ============================
                 お問い合わせフォーム
            ============================ -->
            <section class="form-section">
                <div class="card">

                    {{-- 成功メッセージ --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- エラーメッセージ --}}
                    @if ($errors->any())
                        <div class="alert alert-error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 通信エラーメッセージ --}}
                    @if (session('error'))
                        <div class="alert alert-error">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="card-header">
                        <h2 class="card-title">ご予約フォーム</h2>
                        <p class="card-subtitle">
                            必要事項をご記入の上、送信してください。
                        </p>
                    </div>

                    <div class="card-content">
                        {{-- ★ Laravel のバリデーションを通すフォーム ★ --}}
                        <form action="{{ route('contact.send') }}" method="POST" class="contact-form" novalidate>
                            @csrf

                            <!-- 名前 + 電話 -->
                            <div class="form-grid">
                                <div class="form-field">
                                    <label for="name">お名前 *</label>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        class="input-field @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}"
                                        placeholder="山田 太郎"
                                    >
                                    @error('name')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-field">
                                    <label for="phone">電話番号 *</label>
                                    <input
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        class="input-field @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}"
                                        placeholder="090-1234-5678"
                                    >
                                    @error('phone')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- メール -->
                            <div class="form-field">
                                <label for="email">メールアドレス *</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="input-field @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="example@email.com"
                                >
                                @error('email')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 件名 -->
                            <div class="form-field">
                                <label for="subject">件名 *</label>
                                <input
                                    type="text"
                                    id="subject"
                                    name="subject"
                                    class="input-field @error('subject') is-invalid @enderror"
                                    value="{{ old('subject') }}"
                                    placeholder="お問い合わせの件名をご記入ください"
                                >
                                @error('subject')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- ご要望 -->
                            <div class="form-field">
                                <label for="message">お問い合わせ内容 *</label>
                                <textarea
                                    id="message"
                                    name="message"
                                    class="textarea-field @error('message') is-invalid @enderror"
                                    rows="4"
                                    placeholder="お問い合わせ内容をご記入ください"
                                >{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 送信ボタン -->
                            <button type="submit" class="submit-button">送信する</button>
                        </form>

                    </div>
                </div>
            </section>

            <!-- ============================
                 サイドバー（スマホ視認性UP）
            ============================ -->
            <aside class="sidebar">
                <!-- --- 店舗情報 --- -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">店舗情報</h3>
                    </div>

                    <div class="card-content contact-info">
                        <!-- 住所 -->
                        <div class="contact-item">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     stroke-width="2" stroke="currentColor" fill="none">
                                    <path d="M18 8c0 4.5-6 9-6 9s-6-4.5-6-9a6 6 0 0 1 12 0z"/>
                                    <circle cx="12" cy="8" r="2"/>
                                </svg>
                            </span>
                            <div>
                                <p class="info-label">住所</p>
                                <p class="info-text">
                                    〒283-0104<br>
                                    千葉県山武郡<br>
                                    九十九里町片貝4772
                                </p>
                            </div>
                        </div>

                        <!-- 電話 -->
                        <div class="contact-item">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </span>
                            <div>
                                <p class="info-label">電話番号</p>
                                <p class="info-text">
                                    <a href="tel:08097049500" class="phone-link" aria-label="電話をかける 080-9704-9500">
                                        080-9704-9500
                                    </a>
                                </p>
                            </div>
                        </div>

                        <!-- メール -->
                        <div class="contact-item">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                                    <path d="M22 6L12 13L2 6"/>
                                </svg>
                            </span>
                            <div>
                                <p class="info-label">メールアドレス</p>
                                <p class="info-text">izurararara@yahoo.ne.jp</p>
                            </div>
                        </div>

                        <!-- SNSリンク -->
                        <div class="sns-links">
                            <a href="https://lin.ee/" target="_blank" class="sns-button line">
                                <img src="{{ asset('img/icon-line.svg') }}" alt="LINE" class="sns-icon">
                                LINEでお問い合わせ
                            </a>
                            <a href="https://www.instagram.com/" target="_blank" class="sns-button instagram">
                                <img src="{{ asset('img/icon-instagram.svg') }}" alt="Instagram" class="sns-icon">
                                Instagramを見る
                            </a>
                        </div>
                    </div>
                </div>

                <!-- --- 営業時間 --- -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title with-icon">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M12 6v6l4 2"/>
                                </svg>
                            </span>
                            営業時間
                        </h3>
                    </div>

                    <div class="card-content">
                        <div class="hours-list">
                            <div class="hours-item">
                                <span class="day">火曜日〜日曜日</span>
                                <span class="time">17:00〜22:00</span>
                            </div>
                            <div class="hours-item">
                                <span class="day">月曜日</span>
                                <span class="time">定休日</span>
                            </div>
                        </div>

                        <div class="notice-box">
                            <p>
                                ※ 営業時間外の日中の法事・宴会も予約にて承ります（最大30名様）。<br>
                                お気軽にご相談ください。
                            </p>
                        </div>
                    </div>
                </div>

                <!-- --- お急ぎの方 --- -->
                <div class="quick-contact-card">
                    <div class="quick-contact-content">
                        <h3>お急ぎの方は</h3>
                        <p>お電話でのご予約も承っております</p>
                        <a href="tel:08097049500" class="phone-button">電話で予約</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
