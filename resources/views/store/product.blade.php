@extends('layouts.store')

@section('content')

@php
    $isSellable = $product->isSellable();
    $name = strtolower($product->name);
    $categoryLabel = strcasecmp((string) $product->category, 'Direct Top-Up') === 0
        ? __('store.direct_topup')
        : $product->category;

    $descriptions = [
        'az' => [
            'pubg' => 'PUBG Mobile hesabı üçün UC top-up məhsulu. Məhsul aktivləşdirildikdə tələb olunan oyun hesabı məlumatlarını daxil edib sifariş prosesinə davam edə biləcəksən.',
            'free' => 'Free Fire hesabı üçün Diamonds top-up məhsulu. Məhsul aktivləşdirildikdə tələb olunan oyun hesabı məlumatlarını daxil edib sifariş prosesinə davam edə biləcəksən.',
            'mobile legends' => 'Mobile Legends: Bang Bang hesabı üçün Diamonds top-up məhsulu. Məhsul aktivləşdirildikdə tələb olunan oyun hesabı məlumatlarını daxil edib sifariş prosesinə davam edə biləcəksən.',
        ],
        'en' => [
            'pubg' => 'UC top-up product for PUBG Mobile accounts. Once activated, you will be able to enter the required game-account details and continue with the order flow.',
            'free' => 'Diamonds top-up product for Free Fire accounts. Once activated, you will be able to enter the required game-account details and continue with the order flow.',
            'mobile legends' => 'Diamonds top-up product for Mobile Legends: Bang Bang accounts. Once activated, you will be able to enter the required game-account details and continue with the order flow.',
        ],
        'ru' => [
            'pubg' => 'Пополнение UC для аккаунтов PUBG Mobile. После активации товара вы сможете указать необходимые данные игрового аккаунта и продолжить оформление заказа.',
            'free' => 'Пополнение Diamonds для аккаунтов Free Fire. После активации товара вы сможете указать необходимые данные игрового аккаунта и продолжить оформление заказа.',
            'mobile legends' => 'Пополнение Diamonds для аккаунтов Mobile Legends: Bang Bang. После активации товара вы сможете указать необходимые данные игрового аккаунта и продолжить оформление заказа.',
        ],
    ];

    $descriptionKey = str_contains($name, 'pubg')
        ? 'pubg'
        : (str_contains($name, 'free')
            ? 'free'
            : (str_contains($name, 'mobile legends') ? 'mobile legends' : null));

    $localizedDescription = $descriptionKey
        ? ($descriptions[app()->getLocale()][$descriptionKey] ?? null)
        : null;
@endphp

<section class="mx-auto max-w-7xl px-5 py-14 lg:px-8 lg:py-16">
    <div class="grid gap-10 lg:grid-cols-[0.95fr_1.05fr] lg:items-center">
        <div class="min-h-[360px] overflow-hidden rounded-[2rem] border border-white/[0.08] bg-[#0C101B] shadow-2xl shadow-black/20 sm:min-h-[420px]">
            @include('store.partials.product-visual', ['product' => $product])
        </div>

        <div class="flex flex-col justify-center">
            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full border border-violet-400/20 bg-violet-500/10 px-3 py-1 text-xs font-medium text-violet-300">
                    {{ $categoryLabel }}
                </span>

                @if($isSellable)
                    <span class="rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-300">
                        {{ __('store.product_status_available') }}
                    </span>
                @else
                    <span class="rounded-full border border-amber-400/20 bg-amber-500/10 px-3 py-1 text-xs font-medium text-amber-300">
                        {{ __('store.review_preview') }}
                    </span>
                @endif
            </div>

            <h1 class="mt-6 text-4xl font-black tracking-tight sm:text-5xl">
                {{ $product->name }}
            </h1>

            <p class="mt-5 max-w-xl leading-7 text-slate-400">
                {{ $localizedDescription ?: ($product->description ?: __('store.product_fallback_description')) }}
            </p>

            <div class="mt-8">
                <div class="text-sm text-slate-500">{{ __('store.price') }}</div>

                @if($product->price !== null)
                    <div class="mt-1 text-4xl font-black">
                        {{ number_format((float) $product->price, 2) }}
                        {{ $product->currency }}
                    </div>
                @else
                    <div class="mt-2 text-xl font-semibold text-slate-200">
                        {{ __('store.pending_activation') }}
                    </div>
                    <p class="mt-2 max-w-lg text-sm leading-6 text-slate-500">
                        {{ __('store.final_price_note') }}
                    </p>
                @endif
            </div>

            <div class="mt-8 grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                    <div class="text-xs text-slate-500">{{ __('store.delivery') }}</div>
                    <div class="mt-1 text-sm font-medium">{{ __('store.digital_fulfillment') }}</div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                    <div class="text-xs text-slate-500">{{ __('store.purchase_status') }}</div>
                    <div class="mt-1 text-sm font-medium">
                        {{ $isSellable ? __('store.available') : __('store.review_preview') }}
                    </div>
                </div>
            </div>

            @if($isSellable)
                <div class="mt-5 rounded-2xl border border-amber-400/20 bg-amber-400/5 p-4 text-sm leading-6 text-amber-100/80">
                    {{ __('store.checkout_warning') }}
                </div>

                <a
                    href="{{ route('checkout.show', $product) }}"
                    class="pc-button mt-7 flex items-center justify-center rounded-2xl px-6 py-4 font-semibold text-white transition"
                >
                    {{ __('store.continue_checkout') }}
                </a>
            @else
                <div class="mt-7 rounded-2xl border border-amber-400/15 bg-amber-400/[0.045] p-5">
                    <div class="font-semibold text-amber-100">
                        {{ __('store.bank_review_preview') }}
                    </div>
                    <p class="mt-2 text-sm leading-6 text-slate-400">
                        {{ __('store.bank_review_preview_text') }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</section>

@endsection
