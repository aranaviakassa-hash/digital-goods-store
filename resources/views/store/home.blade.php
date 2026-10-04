@extends('layouts.store')

@section('content')
<section class="relative isolate overflow-hidden border-b border-white/[0.07]">
    <div class="absolute inset-0 -z-30 bg-[#060712]"></div>
    <div class="absolute inset-0 -z-20 opacity-90" style="background:radial-gradient(circle at 12% 22%,rgba(34,211,238,.14),transparent 26rem),radial-gradient(circle at 76% 18%,rgba(124,58,237,.28),transparent 34rem),radial-gradient(circle at 88% 72%,rgba(236,72,153,.14),transparent 28rem);"></div>
    <div class="absolute inset-x-0 top-0 -z-10 h-px bg-gradient-to-r from-transparent via-cyan-300/30 to-transparent"></div>

    <div class="mx-auto grid min-h-[720px] max-w-7xl items-center gap-14 px-5 py-20 lg:grid-cols-[.92fr_1.08fr] lg:px-8 lg:py-24">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-3 rounded-full border border-cyan-400/20 bg-cyan-400/[0.08] px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-cyan-100">
                <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_14px_rgba(52,211,153,.9)]"></span>
                {{ __('store.hero_badge') }}
            </div>

            <h1 class="mt-8 text-[3.5rem] font-black leading-[.92] tracking-[-0.055em] text-white sm:text-[4.6rem] lg:text-[5.25rem]">
                {{ __('store.hero_title_1') }}
                <span class="mt-2 block pc-brand-text">{{ __('store.hero_title_2') }}</span>
            </h1>

            <p class="mt-7 max-w-xl text-base leading-8 text-slate-400 sm:text-lg">
                {{ __('store.hero_text') }}
            </p>

            <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('products.index') }}" class="pc-button inline-flex min-h-14 items-center justify-center gap-2 rounded-2xl px-7 py-4 font-bold text-white transition hover:-translate-y-0.5">
                    {{ __('store.browse_products') }} <span>→</span>
                </a>
                <a href="{{ route('orders.track') }}" class="inline-flex min-h-14 items-center justify-center rounded-2xl border border-white/10 bg-white/[0.035] px-7 py-4 font-semibold text-slate-200 transition hover:border-violet-400/30 hover:bg-white/[0.07]">
                    {{ __('store.track_an_order') }}
                </a>
            </div>

            <div class="mt-10 grid max-w-xl grid-cols-3 gap-3">
                @foreach([
                    [__('store.trust_secure_title'), __('store.trust_secure_text')],
                    [__('store.trust_tracking_title'), __('store.trust_tracking_text')],
                    [__('store.trust_support_title'), __('store.trust_support_text')],
                ] as [$title, $text])
                    <div class="rounded-2xl border border-white/[0.07] bg-white/[0.025] p-4">
                        <div class="text-xs font-bold text-white">{{ $title }}</div>
                        <div class="mt-1 text-[11px] leading-5 text-slate-500">{{ $text }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="relative mx-auto w-full max-w-2xl lg:mx-0">
            <div class="absolute -inset-8 -z-10 rounded-[3rem] bg-gradient-to-br from-cyan-400/10 via-violet-500/20 to-fuchsia-500/10 blur-3xl"></div>
            <div class="relative rounded-[2rem] border border-white/[0.09] bg-[#0b0d1b]/88 p-4 shadow-[0_30px_90px_rgba(0,0,0,.45)] backdrop-blur-xl sm:p-5">
                <div class="flex items-center justify-between gap-4 border-b border-white/[0.07] pb-4">
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-[0.24em] text-slate-600">{{ __('store.trending_now') }}</div>
                        <div class="mt-1 text-lg font-black text-white">{{ __('store.game_topups') }}</div>
                    </div>
                    <span class="rounded-full border border-amber-300/15 bg-amber-300/[0.07] px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-amber-200">{{ __('store.online') }}</span>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    @forelse($products->take(3) as $product)
                        @php
                            $categoryLabel = strcasecmp((string) $product->category, 'Direct Top-Up') === 0 ? __('store.direct_topup') : $product->category;
                        @endphp
                        <a href="{{ route('products.show', $product) }}" class="group overflow-hidden rounded-[1.35rem] border border-white/[0.07] bg-[#0d1020] transition duration-300 hover:-translate-y-1 hover:border-violet-400/30 hover:bg-[#11152a]">
                            <div class="h-36 overflow-hidden border-b border-white/[0.06]">
                                @include('store.partials.product-visual', ['product' => $product])
                            </div>
                            <div class="p-4">
                                <div class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-600">{{ $categoryLabel }}</div>
                                <div class="mt-2 min-h-12 text-sm font-black leading-5 text-white">{{ $product->name }}</div>
                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <span class="inline-flex items-center gap-1.5 text-[10px] font-semibold text-amber-300">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-300"></span>{{ __('store.review_preview') }}
                                    </span>
                                    <span class="text-slate-600 transition group-hover:translate-x-1 group-hover:text-white">→</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-3 rounded-2xl border border-dashed border-white/10 p-8 text-center text-sm text-slate-500">{{ __('store.catalog_preparing') }}</div>
                    @endforelse
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl border border-white/[0.07] bg-black/20 p-4">
                        <div class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-600">{{ __('store.delivery_estimate') }}</div>
                        <div class="mt-1 text-sm font-bold text-cyan-300">{{ __('store.delivery_1_5_minutes') }}</div>
                    </div>
                    <div class="rounded-2xl border border-white/[0.07] bg-black/20 p-4">
                        <div class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-600">{{ __('store.registered_business') }}</div>
                        <div class="mt-1 truncate text-sm font-bold text-slate-200">{{ config('company.legal_short_name') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-white/[0.07] bg-white/[0.018]">
    <div class="mx-auto grid max-w-7xl gap-4 px-5 py-7 sm:grid-cols-2 lg:grid-cols-4 lg:px-8">
        @foreach([
            ['01', __('store.step_choose_title'), __('store.step_choose_text')],
            ['02', __('store.step_details_title'), __('store.step_details_text')],
            ['03', __('store.step_payment_title'), __('store.step_payment_text')],
            ['04', __('store.step_delivery_title'), __('store.step_delivery_text')],
        ] as [$number, $title, $text])
            <div class="rounded-2xl border border-white/[0.06] bg-[#0a0c18] p-5">
                <div class="text-[10px] font-black tracking-[0.18em] text-violet-400">{{ $number }}</div>
                <div class="mt-3 text-sm font-bold text-white">{{ $title }}</div>
                <p class="mt-1 text-xs leading-5 text-slate-500">{{ $text }}</p>
            </div>
        @endforeach
    </div>
</section>

<section class="relative overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.22em] text-cyan-400">{{ __('store.featured') }}</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">{{ __('store.popular_products') }}</h2>
                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-500">{{ __('store.popular_products_text') }}</p>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-cyan-300 transition hover:text-white">{{ __('store.view_all') }} →</a>
        </div>

        <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse($products as $product)
                @include('store.partials.home-product-card', ['product' => $product])
            @empty
                <div class="col-span-full rounded-[1.6rem] border border-dashed border-white/10 bg-white/[0.02] px-6 py-14 text-center">
                    <div class="font-semibold text-white">{{ __('store.catalog_preparing') }}</div>
                    <div class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">{{ __('store.catalog_preparing_text') }}</div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="border-y border-white/[0.07] bg-[#090b17]/80">
    <div class="mx-auto grid max-w-7xl gap-8 px-5 py-20 lg:grid-cols-[1.15fr_.85fr] lg:px-8">
        <div class="rounded-[2rem] border border-white/[0.07] bg-[#0b0e1b] p-8 sm:p-10">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-violet-400">{{ __('store.why_nexora_eyebrow') }}</p>
            <h2 class="mt-4 max-w-2xl text-3xl font-black tracking-tight sm:text-4xl">{{ __('store.why_nexora_title') }}</h2>
            <p class="mt-5 max-w-2xl text-sm leading-7 text-slate-400">{{ __('store.why_nexora_text') }}</p>
            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                @foreach([
                    [__('store.why_tracking_title'), __('store.why_tracking_text')],
                    [__('store.why_support_title'), __('store.why_support_text')],
                    [__('store.why_company_title'), __('store.why_company_text')],
                    [__('store.trust_secure_title'), __('store.trust_secure_text')],
                ] as [$title, $text])
                    <div class="rounded-2xl border border-white/[0.06] bg-white/[0.025] p-5">
                        <div class="font-bold text-white">{{ $title }}</div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">{{ $text }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="relative overflow-hidden rounded-[2rem] border border-violet-400/20 bg-gradient-to-br from-violet-600/15 via-[#0f1225] to-cyan-500/10 p-8 sm:p-10">
            <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-fuchsia-400/15 blur-3xl"></div>
            <div class="relative">
                <div class="text-xs font-bold uppercase tracking-[0.2em] text-violet-300">{{ __('store.registered_business') }}</div>
                <div class="mt-5 text-2xl font-black text-white">{{ config('company.legal_short_name') }}</div>
                <p class="mt-4 text-sm leading-7 text-slate-400">{{ __('store.company_transparency_text') }}</p>
                <dl class="mt-7 space-y-4 text-sm">
                    <div class="flex justify-between gap-4 border-b border-white/[0.06] pb-3"><dt class="text-slate-500">{{ __('store.tax_id') }}</dt><dd class="font-semibold text-slate-200">{{ config('company.tax_id') }}</dd></div>
                    <div class="flex justify-between gap-4 border-b border-white/[0.06] pb-3"><dt class="text-slate-500">{{ __('store.phone') }}</dt><dd class="font-semibold text-slate-200">{{ config('company.phone_display') }}</dd></div>
                    <div class="flex flex-col gap-2"><dt class="text-slate-500">{{ __('store.address') }}</dt><dd class="font-semibold leading-6 text-slate-200">{{ config('company.legal_address') }}</dd></div>
                </dl>
                <a href="{{ route('contact') }}" class="mt-8 inline-flex items-center gap-2 text-sm font-bold text-cyan-300 transition hover:text-white">{{ __('store.contact_us') }} →</a>
            </div>
        </div>
    </div>
</section>

<section class="pb-24 pt-12">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-r from-blue-600/15 via-violet-600/20 to-fuchsia-600/15 px-7 py-10 text-center sm:px-10">
            <h2 class="text-3xl font-black text-white">{{ __('store.final_cta_title') }}</h2>
            <p class="mx-auto mt-3 max-w-2xl text-sm leading-7 text-slate-400">{{ __('store.final_cta_text') }}</p>
            <a href="{{ route('products.index') }}" class="pc-button mt-7 inline-flex items-center justify-center rounded-2xl px-7 py-4 font-bold text-white">{{ __('store.browse_products') }} →</a>
        </div>
    </div>
</section>
@endsection
