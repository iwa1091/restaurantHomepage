@extends('layout.app')

@section('title', 'お品書き | すし割烹 いづ浦')

{{-- ページ専用CSS（新デザイン） --}}
@section('styles')
    @vite(['resources/css/pages/menu_price/menu_price.css'])
@endsection

@section('content')
<div class="menu-page-container">
    <div class="menu-inner">

        {{-- ページヘッダー --}}
        <div class="menu-header">
            <h1 class="menu-title">お品書き</h1>
            <p class="menu-description">
                九十九里の新鮮な海の幸を中心に、旬の食材を活かした料理をご用意しております。<br>
                季節により内容が変わる場合がございます。
            </p>
        </div>

        {{-- カテゴリごとにループ --}}
        @forelse ($categories as $category)
            @php
                // 有効なサービスのみ取得
                $activeServices = $category->services->where('is_active', true);
            @endphp

            @if ($activeServices->isNotEmpty())
                <section class="menu-section">
                    <h2 class="section-title">{{ $category->name }}</h2>

                    {{-- カテゴリ説明 --}}
                    @if($category->description)
                        <p class="category-description">{{ $category->description }}</p>
                    @endif

                    {{-- サービス一覧 --}}
                    <div class="menu-grid">
                        @foreach ($activeServices as $service)
                            <div class="menu-card @if($service->is_popular) menu-card-popular @endif">

                                {{-- 人気No.1バッジ --}}
                                @if($service->is_popular)
                                    <span class="popular-badge">人気No.1</span>
                                @endif

                                {{-- サービス画像 --}}
                                @if($service->image)
                                    <div class="card-image">
                                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}">

                                        {{-- 特徴バッジ --}}
                                        @if(!empty($service->features))
                                            <div class="feature-badges">
                                                @foreach($service->features as $feature)
                                                    <span class="feature-badge">{{ $feature }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                {{-- タイトル・説明 --}}
                                <div class="card-header">
                                    <h3 class="card-title">{{ $service->name }}</h3>

                                    @if($service->description)
                                        <p class="card-description">{!! nl2br(e($service->description)) !!}</p>
                                    @endif
                                </div>

                                {{-- 価格と予約ボタン --}}
                                <div class="card-content">
                                    <div class="card-price-info">
                                        <span class="card-price">¥{{ number_format($service->price) }}</span>
                                    </div>
                                    <a href="{{ route('reservation.form', ['service_id' => $service->id]) }}" class="button-primary btn-reserve">
                                        ご予約・お問い合わせ
                                    </a>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

        @empty
            <p class="no-service">現在、登録されているメニューはありません。</p>
        @endforelse

        {{-- 注意事項 --}}
        <section class="notes-section">
            <h3 class="notes-title">ご案内</h3>
            <div class="notes-grid">
                <div class="note-item">
                    <p>・料金は税込価格です。</p>
                    <p>・仕入れ状況により、メニュー内容・価格が変更になる場合がございます。</p>
                    <p>・アレルギーをお持ちの方は、事前にご相談ください。</p>
                </div>
                <div class="note-item">
                    <p>・宴会・法事のコース料理は要予約制です（最大30名様まで対応）。</p>
                    <p>・営業時間外の日中の法事・宴会もご予約にて承ります。</p>
                    <p>・ご不明点はお気軽にお電話（080-9704-9500）にてお問い合わせください。</p>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection
