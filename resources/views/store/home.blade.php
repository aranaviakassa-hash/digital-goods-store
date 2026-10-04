@extends('layouts.store')

@section('content')

<section class="relative isolate overflow-hidden">
    <div class="absolute inset-0 -z-20 bg-[#070816]"></div>
    <div class="absolute inset-0 -z-10 opacity-95" style="background:radial-gradient(circle at 12% 12%,rgba(34,211,238,.16),transparent 30%),radial-gradient(circle at 82% 18%,rgba(124,58,237,.24),transparent 35%),radial-gradient(circle at 72% 82%,rgba(236,72,153,.10),transparent 28%);"></div>
    <div class="pointer-events-none absolute left-1/2 top-16 -z-10 h-80 w-80 -translate-x-1/2 rounded-full border border-white/[0.035]"></div>

    <div class="mx-auto grid max-w-7xl items-center gap-16 px-5 py-20 lg:grid-cols-[1.02fr_.98fr] lg:px-8 lg:py-24 xl:py-28">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 rounded-full border border-cyan-400/20 bg-cyan-400/[0.07] px-4 py-2 text-xs font-semibold text-cyan-200 backdrop-blur">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-40"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                </span>
                {{ __('store.hero_badge') }}
            </div>

            <h1 class="mt-7 max-w-[760px] text-5xl font-black leading-[.96] tracking-[-0.05em] text-white sm:text-6xl lg:text-[4.6rem]">
                {{ __('store.hero_title_1') }}
                <span class="mt-2 block pc-brand-text">{{ __('store.hero_title_2') }}</span>
            </h1>

            <p class="mt-7 max-w-2xl text-base leading-8 text-slate-400 sm:text-lg">
                {{ __('store.hero_text') }}
            </p>

            <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('products.index') }}" class="pc-button group inline-flex items-center justify-center gap-2 rounded-2xl px-7 py-4 font-bold text-white transition hover:-translate-y-0.5">
                    {{ __('store.browse_products') }}
                    <span class="transition group-hover:translate-x-1">→</span>
                </a>
                <a href="{{ route('orders.track') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/10 bg-white/[0.035] px-7 py-4 font-semibold text-slate-200 backdrop-blur transition hover:border-cyan-400/30 hover:bg-white/[0.06]">
                    {{ __('store.track_an_order') }}
                </a>
            </div>

            <div class="mt-10 grid max-w-2xl gap-3 sm:grid-cols-3">
                @foreach([
                    ['✓', __('store.hero_point_secure')],
                    ['⌁', __('store.hero_point_tracking')],
                    ['AZ', __('store.hero_point_support')],
                ] as [$icon, $label])
                    <div class="flex items-center gap-3 rounded-xl border border-white/[0.07] bg-white/[0.025] px-4 py-3 text-xs font-medium text-slate-400 backdrop-blur">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-white/[0.04] text-[10px] font-black text-emerald-300">{{ $icon }}</span>
                        <span>{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="relative mx-auto w-full max-w-xl lg:mx-0">
            <div class="absolute -inset-10 -z-10 rounded-[4rem] bg-gradient-to-br from-violet-600/18 via-cyan-500/8 to-pink-500/16 blur-3xl"></div>
            <div class="relative overflow-hidden rounded-[2rem] border border-white/[0.09] bg-[#0D1020]/92 p-5 shadow-2xl shadow-black/45 backdrop-blur-xl sm:p-6">
                <div class="pointer-events-none absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-violet-500/[0.07] to-transparent"></div>

                <div class="relative flex items-center justify-between gap-4">
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-[0.22em] text-slate-500">{{ __('store.trending_now') }}</div>
                        <div class="mt-2 text-xl font-black tracking-tight text-white">{{ __('store.game_topups') }}</div>
                    </div>
                    <div class="rounded-full border border-amber-400/20 bg-amber-400/[0.08] px-3 py-1.5 text-[10px] font-semibold text-amber-200">
                        {{ __('store.online') }}
                    </div>
                </div>

                <div class="relative mt-6 grid gap-3">
                    @forelse($products->take(3) as $product)
                        @php
                            $categoryLabel = strcasecmp((string) $product->category, 'Direct Top-Up') === 0
                                ? __('store.direct_topup')
                                : $product->category;
                        @endphp
                        <a href="{{ route('products.show', $product) }}" class="group grid grid-cols-[88px_1fr_auto] items-center gap-4 overflow-hidden rounded-2xl border border-white/[0.07] bg-black/20 p-3 transition hover:border-violet-400/30 hover:bg-white/[0.04]">
                            <div class="h-20 overflow-hidden rounded-xl border border-white/[0.06] bg-[#090B14]">
                                @include('store.partials.product-visual', ['product' => $product])
                            </div>
                            <div class="min-w-0">
                                <div class="truncate font-bold text-white">{{ $product->name }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ $categoryLabel }}</div>
                                <div class="mt-2 inline-flex items-center gap-1.5 rounded-full border border-amber-400/15 bg-amber-400/[0.055] px-2.5 py-1 text-[10px] font-semibold text-amber-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-300"></span>
                                    {{ $product->isSellable() ? __('store.available') : __('store.review_preview') }}
                                </div>
                            </div>
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/[0.07] bg-white/[0.025] text-sm text-slate-500 transition group-hover:border-violet-400/20 group-hover:text-white">→</div>
                        </a>
                    @empty
                        <div class="rounded-2xl border border-dashed border-white/10 p-6 text-center text-sm text-slate-500">
                            {{ __('store.catalog_preparing') }}
                        </div>
                    @endforelse
                </div>

                <div class="relative mt-5 grid grid-cols-2 gap-3 text-xs">
                    <div class="rounded-2xl border border-white/[0.07] bg-black/20 p-4">
                        <span class="text-slate-500">{{ __('store.delivery_estimate') }}</span>
                        <div class="mt-1 font-semibold text-cyan-300">{{ __('store.delivery_1_5_minutes') }}</div>
                    </div>
                    <div class="rounded-2xl border border-white/[0.07] bg-black/20 p-4">
                        <span class="text-slate-500">{{ __('store.trust_tracking_title') }}</span>
                        <div class="mt-1 font-semibold text-violet-300">{{ __('store.trust_tracking_text') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-y border-white/[0.07] bg-white/[0.02]">
    <div class="mx-auto grid max-w-7xl grid-cols-2 divide-x divide-y divide-white/[0.07] px-5 sm:grid-cols-4 sm:divide-y-0 lg:px-8">
        @foreach([
            ['✓', 'text-emerald-300 bg-emerald-400/10', __('store.trust_secure_title'), __('store.trust_secure_text')],
            ['⚡', 'text-cyan-300 bg-cyan-400/10', __('store.trust_delivery_title'), __('store.trust_delivery_text')],
            ['⌁', 'text-violet-300 bg-violet-400/10', __('store.trust_tracking_title'), __('store.trust_tracking_text')],
            ['◇', 'text-pink-300 bg-pink-400/10', __('store.trust_support_title'), __('store.trust_support_text')],
        ] as [$icon, $iconClass, $title, $text])
            <div class="flex items-center gap-3 px-3 py-5 sm:px-5">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $iconClass }}">{{ $icon }}</div>
                <div>
                    <div class="text-xs font-semibold text-white">{{ $title }}</div>
                    <div class="mt-0.5 text-[11px] text-slate-500">{{ $text }}</div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<section class="relative overflow-hidden">
    <div class="absolute left-0 top-24 -z-10 h-72 w-72 rounded-full bg-violet-600/5 blur-3xl"></div>
    <div class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
        <div class="flex items-end justify-between gap-8">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-400">{{ __('store.featured') }}</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">{{ __('store.popular_products') }}</h2>
                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-500">{{ __('store.popular_products_text') }}</p>
            </div>
            <a href="{{ route('products.index') }}" class="hidden items-center gap-2 rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-slate-300 transition hover:border-violet-400/30 hover:text-white sm:flex">
                {{ __('store.view_all') }} →
            </a>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
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
    <div class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-fuchsia-400">{{ __('store.simple_process') }}</p>
            <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">{{ __('store.how_it_works') }}</h2>
            <p class="mt-3 text-sm leading-6 text-slate-500">{{ __('store.how_it_works_text') }}</p>
        </div>

        <div class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
            @foreach([
                ['01', __('store.step_choose_title'), __('store.step_choose_text')],
                ['02', __('store.step_details_title'), __('store.step_details_text')],
                ['03', __('store.step_payment_title'), __('store.step_payment_text')],
                ['04', __('store.step_delivery_title'), __('store.step_delivery_text')],
            ] as [$number, $title, $text])
                <div class="pc-panel rounded-2xl p-6">
                    <div class="text-xs font-black tracking-[0.18em] text-violet-400">{{ $number }}</div>
                    <h3 class="mt-4 font-bold text-white">{{ $title }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section>
    <div class="mx-auto grid max-w-7xl gap-8 px-5 py-24 lg:grid-cols-[1fr_.8fr] lg:px-8">
        <div class="pc-panel rounded-[2rem] p-8 sm:p-10">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-400">{{ __('store.why_nexora_eyebrow') }}</p>
            <h2 class="mt-4 max-w-2xl text-3xl font-black tracking-tight sm:text-4xl">{{ __('store.why_nexora_title') }}</h2>
            <p class="mt-5 max-w-2xl text-sm leading-7 text-slate-400">{{ __('store.why_nexora_text') }}</p>

            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                @foreach([
                    [__('store.why_tracking_title'), __('store.why_tracking_text')],
                    [__('store.why_support_title'), __('store.why_support_text')],
                    [__('store.why_company_title'), __('store.why_company_text')],
                    [__('store.trust_secure_title'), __('store.trust_secure_text')],
                ] as [$title, $text])
                    <div class="rounded-2xl border border-white/[0.07] bg-black/15 p-5">
                        <div class="font-semibold text-white">{{ $title }}</div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">{{ $text }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="relative overflow-hidden rounded-[2rem] border border-violet-400/20 bg-gradient-to-br from-violet-600/15 via-[#0f1225] to-cyan-500/10 p-8 sm:p-10">
            <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-fuchsia-400/15 blur-3xl"></div>
            <div class="relative">
                <div class="text-xs font-bold uppercase tracking-[0.2em] text-violet-300">{{ __('store.registered_business') }}</div>
                <div class="mt-5 text-xl font-black text-white">{{ config('company.legal_short_name') }}</div>
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

<section class="pb-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-r from-blue-600/15 via-violet-600/20 to-fuchsia-600/15 px-7 py-10 text-center sm:px-10">
            <div class="absolute inset-0 -z-10 bg-black/10"></div>
            <h2 class="text-3xl font-black text-white">{{ __('store.final_cta_title') }}</h2>
            <p class="mx-auto mt-3 max-w-2xl text-sm leading-7 text-slate-400">{{ __('store.final_cta_text') }}</p>
            <a href="{{ route('products.index') }}" class="pc-button mt-7 inline-flex items-center justify-center rounded-2xl px-7 py-4 font-bold text-white">{{ __('store.browse_products') }} →</a>
        </div>
    </div>
</section>

@endsection
