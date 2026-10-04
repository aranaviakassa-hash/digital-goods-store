@extends('layouts.store')

@section('content')
@php
    $isPurchasable = $product->isPurchasableNow();
    $categoryKey = strtolower(trim((string) $product->category));
    $category = match($categoryKey) {
        'direct top-up', 'direct topup' => __('store.direct_topup'),
        'game credits' => __('store.game_credits'),
        'gift cards', 'gift card & pin', 'gift cards & pins' => __('store.gift_cards'),
        default => $product->category,
    };
    $description = app()->getLocale() === 'en' && filled($product->description)
        ? $product->description
        : __('store.product_fallback_description');
@endphp

<section class="relative overflow-hidden border-b border-white/[0.07] bg-[#05070d]">
    <div class="absolute left-[-8rem] top-10 h-80 w-80 rounded-full bg-blue-600/10 blur-[120px]"></div>
    <div class="absolute right-[-6rem] top-24 h-72 w-72 rounded-full bg-violet-600/10 blur-[120px]"></div>

    <div class="mx-auto grid max-w-7xl gap-8 px-5 py-12 lg:grid-cols-[.9fr_1.1fr] lg:items-stretch lg:px-8 lg:py-16">
        <div class="pc-panel-strong min-h-[420px] overflow-hidden rounded-[2rem]">
            @include('store.partials.product-visual', ['product' => $product])
        </div>

        <div class="pc-panel rounded-[2rem] p-6 sm:p-8 lg:p-10">
            <div class="flex flex-wrap items-center gap-2">
                <span class="rounded-full border border-blue-400/20 bg-blue-400/[0.08] px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.14em] text-blue-200">{{ $category }}</span>
                <span class="rounded-full border px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.14em] {{ $isPurchasable ? 'border-emerald-400/20 bg-emerald-400/[0.08] text-emerald-300' : 'border-violet-400/20 bg-violet-400/[0.08] text-violet-300' }}">
                    {{ $isPurchasable ? __('store.product_status_available') : __('store.review_preview') }}
                </span>
            </div>

            <h1 class="mt-6 text-4xl font-black tracking-[-0.04em] text-white sm:text-5xl">{{ $product->name }}</h1>
            <p class="mt-5 max-w-2xl text-sm leading-7 text-slate-400 sm:text-base">{{ $description }}</p>

            <div class="mt-8 border-t border-white/[0.07] pt-7">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <div class="pc-kicker text-slate-600">{{ __('store.price') }}</div>
                        @if($product->price !== null && (float) $product->price > 0)
                            <div class="mt-2 text-4xl font-black tracking-tight text-white">{{ number_format((float) $product->price, 2) }} <span class="text-base font-bold text-slate-500">{{ $product->currency }}</span></div>
                        @else
                            <div class="mt-2 text-lg font-black text-slate-200">{{ __('store.pending_activation') }}</div>
                            <p class="mt-2 max-w-lg text-sm leading-6 text-slate-500">{{ __('store.final_price_note') }}</p>
                        @endif
                    </div>
                    <span class="hidden h-12 w-12 items-center justify-center rounded-2xl border border-emerald-400/15 bg-emerald-400/[0.06] text-emerald-300 sm:flex">◆</span>
                </div>
            </div>

            <div class="mt-7">
                <div class="pc-kicker text-slate-600">{{ __('store.pricing') }}</div>
                <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @if($product->price !== null && (float) $product->price > 0)
                        <div class="rounded-2xl border border-blue-400/35 bg-blue-500/[0.08] p-4 shadow-[0_0_0_1px_rgba(59,130,246,.08)]">
                            <div class="text-xs font-black text-white">{{ $product->name }}</div>
                            <div class="mt-2 text-sm font-black text-blue-200">{{ number_format((float) $product->price, 2) }} {{ $product->currency }}</div>
                        </div>
                    @else
                        @for($i = 0; $i < 3; $i++)
                            <div class="rounded-2xl border border-white/[0.06] bg-black/20 p-4 opacity-70">
                                <div class="h-2 w-12 rounded-full bg-white/[0.07]"></div>
                                <div class="mt-3 text-[11px] font-bold text-slate-500">{{ __('store.pending_activation') }}</div>
                            </div>
                        @endfor
                    @endif
                </div>
            </div>

            <div class="mt-7 grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl border border-white/[0.07] bg-black/20 p-4">
                    <div class="pc-kicker text-[9px] text-slate-700">{{ __('store.delivery') }}</div>
                    <div class="mt-2 text-sm font-black text-white">{{ __('store.digital_fulfillment') }}</div>
                </div>
                <div class="rounded-2xl border border-white/[0.07] bg-black/20 p-4">
                    <div class="pc-kicker text-[9px] text-slate-700">{{ __('store.purchase_status') }}</div>
                    <div class="mt-2 text-sm font-black {{ $isPurchasable ? 'text-emerald-300' : 'text-blue-300' }}">{{ $isPurchasable ? __('store.available') : __('store.review_preview') }}</div>
                </div>
            </div>

            @if($isPurchasable)
                <div class="mt-6 rounded-2xl border border-amber-400/15 bg-amber-400/[0.04] p-4 text-sm leading-6 text-amber-100/80">{{ __('store.checkout_warning') }}</div>
                <a href="{{ route('checkout.show', $product) }}" class="pc-button mt-6 flex min-h-14 items-center justify-center rounded-2xl px-6 py-4 font-black text-white transition hover:-translate-y-0.5">{{ __('store.continue_checkout') }} →</a>
            @else
                <div class="mt-6 rounded-2xl border border-blue-400/15 bg-blue-400/[0.045] p-5">
                    <div class="flex items-center gap-2 font-black text-blue-100"><span class="h-2 w-2 rounded-full bg-blue-400"></span>{{ __('store.bank_review_preview') }}</div>
                    <p class="mt-2 text-sm leading-6 text-slate-400">{{ __('store.bank_review_preview_text') }}</p>
                    @if(!config('company.live_payment_enabled'))
                        <a href="{{ route('checkout.review', $product) }}" class="mt-5 inline-flex min-h-12 items-center gap-2 rounded-xl border border-blue-400/20 bg-blue-400/[0.08] px-5 text-sm font-black text-blue-100 transition hover:border-blue-300/40 hover:bg-blue-400/[0.13] hover:text-white">{{ __('store.bank_review_preview') }} →</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
