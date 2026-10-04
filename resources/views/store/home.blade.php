@extends('layouts.store')

@section('content')
<section class="pc-noise relative isolate overflow-hidden border-b border-white/[0.07]">
    <div class="absolute inset-0 -z-30 bg-[#05070d]"></div>
    <div class="absolute left-[-10rem] top-24 -z-20 h-[34rem] w-[34rem] rounded-full bg-blue-600/15 blur-[120px]"></div>
    <div class="absolute right-[-8rem] top-6 -z-20 h-[30rem] w-[30rem] rounded-full bg-emerald-500/10 blur-[120px]"></div>
    <div class="absolute right-[28%] top-[35%] -z-20 h-[24rem] w-[24rem] rounded-full bg-violet-600/10 blur-[120px]"></div>

    <div class="mx-auto grid min-h-[760px] max-w-7xl items-center gap-14 px-5 py-16 lg:grid-cols-[.9fr_1.1fr] lg:px-8 lg:py-20">
        <div class="relative z-10 max-w-2xl">
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-400/[0.07] px-3.5 py-2 text-[11px] font-black uppercase tracking-[0.16em] text-emerald-200">
                <span class="pc-status-dot h-1.5 w-1.5 rounded-full bg-emerald-400 text-emerald-400"></span>
                {{ __('store.hero_badge') }}
            </div>

            <h1 class="mt-7 max-w-3xl text-[3.2rem] font-black leading-[.94] tracking-[-0.055em] text-white sm:text-[4.5rem] lg:text-[5.35rem]">
                {{ __('store.hero_title_1') }}
                <span class="mt-2 block pc-brand-text">{{ __('store.hero_title_2') }}</span>
            </h1>

            <p class="mt-7 max-w-xl text-base leading-8 text-slate-400 sm:text-lg">
                {{ __('store.hero_text') }}
            </p>

            <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('products.index') }}" class="pc-button inline-flex min-h-14 items-center justify-center gap-2 rounded-2xl px-7 py-4 font-black text-white transition hover:-translate-y-0.5">
                    {{ __('store.browse_products') }} <span aria-hidden="true">→</span>
                </a>
                <a href="{{ route('orders.track') }}" class="inline-flex min-h-14 items-center justify-center rounded-2xl border border-white/10 bg-white/[0.035] px-7 py-4 font-bold text-slate-200 transition hover:border-blue-400/30 hover:bg-blue-400/[0.06] hover:text-white">
                    {{ __('store.track_an_order') }}
                </a>
            </div>

            <div class="mt-10 grid max-w-2xl gap-3 sm:grid-cols-3">
                @foreach([
                    [__('store.trust_secure_title'), __('store.trust_secure_text'), 'blue'],
                    [__('store.trust_tracking_title'), __('store.trust_tracking_text'), 'emerald'],
                    [__('store.trust_support_title'), __('store.trust_support_text'), 'violet'],
                ] as [$title, $text, $tone])
                    <div class="pc-panel rounded-2xl p-4">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full {{ $tone === 'blue' ? 'bg-blue-400' : ($tone === 'emerald' ? 'bg-emerald-400' : 'bg-violet-400') }}"></span>
                            <div class="text-xs font-black text-white">{{ $title }}</div>
                        </div>
                        <div class="mt-2 text-[11px] leading-5 text-slate-500">{{ $text }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="relative mx-auto w-full max-w-[680px] lg:mx-0">
            <div class="absolute -inset-7 -z-10 rounded-[3rem] bg-gradient-to-br from-blue-500/12 via-violet-500/10 to-emerald-400/10 blur-3xl"></div>

            <div class="pc-panel-strong relative overflow-hidden rounded-[2rem] p-4 sm:p-5">
                <div class="absolute right-0 top-0 h-40 w-40 rounded-full bg-blue-500/10 blur-3xl"></div>
                <div class="relative flex items-center justify-between gap-4 border-b border-white/[0.07] pb-4">
                    <div>
                        <div class="pc-kicker text-slate-600">{{ __('store.trending_now') }}</div>
                        <div class="mt-1 text-xl font-black text-white">{{ __('store.game_topups') }}</div>
                    </div>
                    <span class="rounded-full border border-blue-400/20 bg-blue-400/[0.08] px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-blue-200">{{ __('store.online') }}</span>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                    @forelse($products->take(3) as $product)
                        @php
                            $name = strtolower($product->name);
                            $tone = str_contains($name, 'pubg') ? 'amber' : (str_contains($name, 'free') ? 'cyan' : 'violet');
                        @endphp
                        <a href="{{ route('products.show', $product) }}" class="pc-game-orbit group relative overflow-hidden rounded-[1.35rem] border border-white/[0.08] bg-[#0a0f1a] transition duration-300 hover:-translate-y-1 hover:border-blue-400/30 hover:shadow-[0_18px_50px_rgba(59,130,246,.12)]">
                            <div class="h-40 overflow-hidden border-b border-white/[0.06]">
                                @include('store.partials.product-visual', ['product' => $product])
                            </div>
                            <div class="p-4">
                                <div class="text-[10px] font-black uppercase tracking-[0.14em] {{ $tone === 'amber' ? 'text-amber-300' : ($tone === 'cyan' ? 'text-cyan-300' : 'text-violet-300') }}">{{ __('store.direct_topup') }}</div>
                                <div class="mt-2 min-h-12 text-sm font-black leading-5 text-white">{{ $product->name }}</div>
                                <div class="mt-4 flex items-center justify-between gap-3 border-t border-white/[0.06] pt-3">
                                    <span class="text-[10px] font-bold text-slate-500">{{ $product->isPurchasableNow() ? __('store.available') : __('store.review_preview') }}</span>
                                    <span class="text-slate-600 transition group-hover:translate-x-1 group-hover:text-blue-300">→</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-3 rounded-2xl border border-dashed border-white/10 p-10 text-center text-sm text-slate-500">{{ __('store.catalog_preparing') }}</div>
                    @endforelse
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl border border-white/[0.07] bg-black/20 p-4">
                        <div class="pc-kicker text-slate-700">{{ __('store.delivery_estimate') }}</div>
                        <div class="mt-2 text-sm font-black text-emerald-300">{{ __('store.delivery_1_5_minutes') }}</div>
                    </div>
                    <div class="rounded-2xl border border-white/[0.07] bg-black/20 p-4">
                        <div class="pc-kicker text-slate-700">{{ __('store.registered_business') }}</div>
                        <div class="mt-2 truncate text-sm font-black text-slate-200">{{ config('company.legal_short_name') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-white/[0.07] bg-[#070b13]/82">
    <div class="mx-auto grid max-w-7xl gap-3 px-5 py-6 sm:grid-cols-2 lg:grid-cols-4 lg:px-8">
        @foreach([
            ['01', __('store.step_choose_title'), __('store.step_choose_text')],
            ['02', __('store.step_details_title'), __('store.step_details_text')],
            ['03', __('store.step_payment_title'), __('store.step_payment_text')],
            ['04', __('store.step_delivery_title'), __('store.step_delivery_text')],
        ] as [$number, $title, $text])
            <div class="group rounded-2xl border border-white/[0.06] bg-white/[0.02] p-5 transition hover:border-blue-400/20 hover:bg-blue-400/[0.035]">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black tracking-[0.18em] text-blue-400">{{ $number }}</span>
                    <span class="h-px w-8 bg-gradient-to-r from-blue-400/60 to-transparent"></span>
                </div>
                <div class="mt-4 text-sm font-black text-white">{{ $title }}</div>
                <p class="mt-2 text-xs leading-5 text-slate-500">{{ $text }}</p>
            </div>
        @endforeach
    </div>
</section>

<section class="relative overflow-hidden">
    <div class="absolute left-1/2 top-16 -z-10 h-72 w-72 -translate-x-1/2 rounded-full bg-blue-500/8 blur-[100px]"></div>
    <div class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="pc-kicker text-emerald-400">{{ __('store.featured') }}</p>
                <h2 class="mt-3 text-3xl font-black tracking-[-0.03em] text-white sm:text-4xl">{{ __('store.popular_products') }}</h2>
                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-500">{{ __('store.popular_products_text') }}</p>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex min-h-11 items-center gap-2 text-sm font-black text-blue-300 transition hover:text-white">{{ __('store.view_all') }} →</a>
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

<section class="border-y border-white/[0.07] bg-[#070b13]/85">
    <div class="mx-auto grid max-w-7xl gap-6 px-5 py-20 lg:grid-cols-[1.08fr_.92fr] lg:px-8">
        <div class="pc-panel rounded-[2rem] p-8 sm:p-10">
            <p class="pc-kicker text-blue-400">{{ __('store.why_nexora_eyebrow') }}</p>
            <h2 class="mt-4 max-w-2xl text-3xl font-black tracking-[-0.03em] text-white sm:text-4xl">{{ __('store.why_nexora_title') }}</h2>
            <p class="mt-5 max-w-2xl text-sm leading-7 text-slate-400">{{ __('store.why_nexora_text') }}</p>

            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                @foreach([
                    [__('store.why_tracking_title'), __('store.why_tracking_text'), 'blue'],
                    [__('store.why_support_title'), __('store.why_support_text'), 'emerald'],
                    [__('store.why_company_title'), __('store.why_company_text'), 'violet'],
                    [__('store.trust_secure_title'), __('store.trust_secure_text'), 'cyan'],
                ] as [$title, $text, $tone])
                    <div class="rounded-2xl border border-white/[0.06] bg-black/20 p-5">
                        <div class="mb-3 h-1 w-10 rounded-full {{ $tone === 'blue' ? 'bg-blue-400' : ($tone === 'emerald' ? 'bg-emerald-400' : ($tone === 'violet' ? 'bg-violet-400' : 'bg-cyan-400')) }}"></div>
                        <div class="font-black text-white">{{ $title }}</div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">{{ $text }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="relative overflow-hidden rounded-[2rem] border border-emerald-400/15 bg-gradient-to-br from-emerald-500/[0.09] via-[#0a101a] to-blue-500/[0.08] p-8 sm:p-10">
            <div class="absolute -right-12 -top-12 h-44 w-44 rounded-full bg-emerald-400/10 blur-3xl"></div>
            <div class="relative">
                <div class="pc-kicker text-emerald-300">{{ __('store.registered_business') }}</div>
                <div class="mt-5 text-2xl font-black text-white">{{ config('company.legal_short_name') }}</div>
                <p class="mt-4 text-sm leading-7 text-slate-400">{{ __('store.company_transparency_text') }}</p>

                <dl class="mt-7 space-y-4 text-sm">
                    <div class="flex justify-between gap-4 border-b border-white/[0.06] pb-3"><dt class="text-slate-500">{{ __('store.tax_id') }}</dt><dd class="font-bold text-slate-200">{{ config('company.tax_id') }}</dd></div>
                    <div class="flex justify-between gap-4 border-b border-white/[0.06] pb-3"><dt class="text-slate-500">{{ __('store.phone') }}</dt><dd class="font-bold text-slate-200">{{ config('company.phone_display') }}</dd></div>
                    <div class="flex flex-col gap-2"><dt class="text-slate-500">{{ __('store.address') }}</dt><dd class="font-bold leading-6 text-slate-200">{{ config('company.legal_address') }}</dd></div>
                </dl>

                <a href="{{ route('contact') }}" class="mt-8 inline-flex min-h-11 items-center gap-2 text-sm font-black text-emerald-300 transition hover:text-white">{{ __('store.contact_us') }} →</a>
            </div>
        </div>
    </div>
</section>

<section class="py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="relative overflow-hidden rounded-[2rem] border border-blue-400/15 bg-gradient-to-r from-blue-600/[0.12] via-violet-600/[0.1] to-emerald-500/[0.08] px-7 py-12 text-center sm:px-10">
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-blue-300/50 to-transparent"></div>
            <h2 class="text-3xl font-black tracking-[-0.03em] text-white">{{ __('store.final_cta_title') }}</h2>
            <p class="mx-auto mt-3 max-w-2xl text-sm leading-7 text-slate-400">{{ __('store.final_cta_text') }}</p>
            <a href="{{ route('products.index') }}" class="pc-button mt-7 inline-flex min-h-13 items-center justify-center rounded-2xl px-7 py-3.5 font-black text-white">{{ __('store.browse_products') }} →</a>
        </div>
    </div>
</section>
@endsection
