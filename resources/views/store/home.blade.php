@extends('layouts.store')



@section('content')



{{-- ============================================================

    1. HERO

============================================================ --}}

<section class="relative isolate overflow-hidden">



    <div class="absolute inset-0 -z-20 bg-[#070A12]"></div>



    <div

        class="absolute inset-0 -z-10 opacity-90"

        style="

            background:

                radial-gradient(circle at 16% 18%, rgba(6,182,212,.18), transparent 31%),

                radial-gradient(circle at 78% 18%, rgba(124,58,237,.25), transparent 34%),

                radial-gradient(circle at 72% 72%, rgba(236,72,153,.12), transparent 28%);

        "

    ></div>



    <div class="mx-auto grid max-w-7xl items-center gap-14 px-5 py-20 lg:grid-cols-[1.05fr_.95fr] lg:px-8 lg:py-28">



        <div>



            <div class="inline-flex items-center gap-2 rounded-full border border-cyan-400/20 bg-cyan-400/[0.08] px-4 py-2 text-xs font-semibold text-cyan-200">



                <span class="relative flex h-2 w-2">



                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-50"></span>



                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>



                </span>



                {{ __('store.hero_badge') }}



            </div>



            <h1 class="mt-7 max-w-4xl text-5xl font-black leading-[.98] tracking-[-0.045em] text-white sm:text-6xl lg:text-7xl">



                {{ __('store.hero_title_1') }}



                <span class="block bg-gradient-to-r from-cyan-300 via-violet-400 to-pink-400 bg-clip-text text-transparent">

                    {{ __('store.hero_title_2') }}

                </span>



            </h1>



            <p class="mt-7 max-w-2xl text-base leading-8 text-slate-400 sm:text-lg">

                {{ __('store.hero_text') }}

            </p>



            <div class="mt-9 flex flex-col gap-3 sm:flex-row">



                <a

                    href="{{ route('products.index') }}"

                    class="group inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 px-7 py-4 font-bold text-white shadow-2xl shadow-violet-900/30 transition hover:-translate-y-0.5 hover:from-violet-500 hover:to-indigo-500"

                >

                    {{ __('store.browse_products') }}



                    <span class="transition group-hover:translate-x-1">

                        →

                    </span>

                </a>



                <a

                    href="{{ route('orders.track') }}"

                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/10 bg-white/[0.04] px-7 py-4 font-semibold text-slate-200 backdrop-blur transition hover:border-cyan-400/30 hover:bg-white/[0.07]"

                >

                    <span>⌁</span>



                    {{ __('store.track_an_order') }}

                </a>



            </div>



            <div class="mt-10 flex flex-wrap gap-x-7 gap-y-3 text-xs font-medium text-slate-500">



                <span class="flex items-center gap-2">

                    <span class="text-emerald-400">✓</span>

                    {{ __('store.hero_point_secure') }}

                </span>



                <span class="flex items-center gap-2">

                    <span class="text-emerald-400">✓</span>

                    {{ __('store.hero_point_tracking') }}

                </span>



                <span class="flex items-center gap-2">

                    <span class="text-emerald-400">✓</span>

                    {{ __('store.hero_point_support') }}

                </span>



            </div>



        </div>



        {{-- Hero visual --}}

        <div class="relative mx-auto w-full max-w-xl lg:mx-0">



            <div class="absolute -inset-8 -z-10 rounded-[4rem] bg-gradient-to-br from-violet-600/20 via-cyan-500/10 to-pink-500/20 blur-3xl"></div>



            <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[0.045] p-5 shadow-2xl shadow-black/40 backdrop-blur-xl">



                <div class="flex items-center justify-between">



                    <div>



                        <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">

                            {{ __('store.trending_now') }}

                        </div>



                        <div class="mt-1 text-lg font-bold text-white">

                            {{ __('store.game_topups') }}

                        </div>



                    </div>



                    <div class="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-[11px] font-semibold text-emerald-300">

                        {{ __('store.online') }}

                    </div>



                </div>



                <div class="mt-5 grid gap-3">



                    <div class="group relative overflow-hidden rounded-2xl border border-amber-400/10 bg-gradient-to-r from-amber-500/15 via-orange-500/10 to-transparent p-5">



                        <div class="absolute right-0 top-0 h-24 w-24 rounded-full bg-amber-400/10 blur-3xl"></div>



                        <div class="relative flex items-center justify-between gap-4">



                            <div class="flex items-center gap-4">



                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-amber-400/20 bg-amber-400/10 text-lg font-black text-amber-300">

                                    P

                                </div>



                                <div>

                                    <div class="font-bold">

                                        PUBG Mobile

                                    </div>



                                    <div class="mt-1 text-xs text-slate-500">

                                        UC Top-Up

                                    </div>

                                </div>



                            </div>



                            <div class="text-amber-300">

                                →

                            </div>



                        </div>



                    </div>



                    <div class="group relative overflow-hidden rounded-2xl border border-cyan-400/10 bg-gradient-to-r from-cyan-500/15 via-blue-500/10 to-transparent p-5">



                        <div class="absolute right-0 top-0 h-24 w-24 rounded-full bg-cyan-400/10 blur-3xl"></div>



                        <div class="relative flex items-center justify-between gap-4">



                            <div class="flex items-center gap-4">



                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-cyan-400/20 bg-cyan-400/10 text-lg font-black text-cyan-300">

                                    F

                                </div>



                                <div>

                                    <div class="font-bold">

                                        Free Fire

                                    </div>



                                    <div class="mt-1 text-xs text-slate-500">

                                        Diamonds

                                    </div>

                                </div>



                            </div>



                            <div class="text-cyan-300">

                                →

                            </div>



                        </div>



                    </div>



                    <div class="group relative overflow-hidden rounded-2xl border border-pink-400/10 bg-gradient-to-r from-violet-500/15 via-pink-500/10 to-transparent p-5">



                        <div class="absolute right-0 top-0 h-24 w-24 rounded-full bg-pink-400/10 blur-3xl"></div>



                        <div class="relative flex items-center justify-between gap-4">



                            <div class="flex items-center gap-4">



                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-pink-400/20 bg-pink-400/10 text-lg font-black text-pink-300">

                                    M

                                </div>



                                <div>

                                    <div class="font-bold">

                                        Mobile Legends

                                    </div>



                                    <div class="mt-1 text-xs text-slate-500">

                                        Diamonds

                                    </div>

                                </div>



                            </div>



                            <div class="text-pink-300">

                                →

                            </div>



                        </div>



                    </div>



                </div>



                <div class="mt-5 rounded-2xl border border-white/10 bg-black/20 p-4">



                    <div class="flex items-center justify-between text-xs">



                        <span class="text-slate-500">

                            {{ __('store.delivery_estimate') }}

                        </span>



                        <span class="font-semibold text-emerald-300">

                            {{ __('store.delivery_1_5_minutes') }}

                        </span>



                    </div>



                </div>



            </div>



        </div>



    </div>



</section>



{{-- ============================================================

    2. TRUST STRIP

============================================================ --}}

<section class="border-y border-white/[0.07] bg-white/[0.025]">



    <div class="mx-auto grid max-w-7xl grid-cols-2 divide-x divide-y divide-white/[0.07] px-5 sm:grid-cols-4 sm:divide-y-0 lg:px-8">



        <div class="flex items-center gap-3 px-3 py-5 sm:px-5">



            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-400/10 text-emerald-300">

                ✓

            </div>



            <div>

                <div class="text-xs font-semibold text-white">

                    {{ __('store.trust_secure_title') }}

                </div>



                <div class="mt-0.5 text-[11px] text-slate-500">

                    {{ __('store.trust_secure_text') }}

                </div>

            </div>



        </div>



        <div class="flex items-center gap-3 px-3 py-5 sm:px-5">



            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-400/10 text-cyan-300">

                ⚡

            </div>



            <div>

                <div class="text-xs font-semibold text-white">

                    {{ __('store.trust_delivery_title') }}

                </div>



                <div class="mt-0.5 text-[11px] text-slate-500">

                    {{ __('store.trust_delivery_text') }}

                </div>

            </div>



        </div>



        <div class="flex items-center gap-3 px-3 py-5 sm:px-5">



            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-400/10 text-violet-300">

                ⌁

            </div>



            <div>

                <div class="text-xs font-semibold text-white">

                    {{ __('store.trust_tracking_title') }}

                </div>



                <div class="mt-0.5 text-[11px] text-slate-500">

                    {{ __('store.trust_tracking_text') }}

                </div>

            </div>



        </div>



        <div class="flex items-center gap-3 px-3 py-5 sm:px-5">



            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-pink-400/10 text-pink-300">

                ◇

            </div>



            <div>

                <div class="text-xs font-semibold text-white">

                    {{ __('store.trust_support_title') }}

                </div>



                <div class="mt-0.5 text-[11px] text-slate-500">

                    {{ __('store.trust_support_text') }}

                </div>

            </div>



        </div>



    </div>



</section>



{{-- ============================================================

    3. POPULAR PRODUCTS

============================================================ --}}

<section class="relative overflow-hidden">



    <div class="absolute left-0 top-24 -z-10 h-72 w-72 rounded-full bg-violet-600/5 blur-3xl"></div>



    <div class="mx-auto max-w-7xl px-5 py-24 lg:px-8">



        <div class="flex items-end justify-between gap-8">



            <div>



                <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-400">

                    {{ __('store.featured') }}

                </p>



                <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">

                    {{ __('store.popular_products') }}

                </h2>



                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-500">

                    {{ __('store.popular_products_text') }}

                </p>



            </div>



            <a

                href="{{ route('products.index') }}"

                class="hidden items-center gap-2 rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-slate-300 transition hover:border-violet-400/30 hover:text-white sm:flex"

            >

                {{ __('store.view_all') }}

                →

            </a>



        </div>



        <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-3">



            @forelse($products as $product)

                @include('store.partials.home-product-card', [
                    'product' => $product,
                ])

            @empty



                <div class="col-span-full rounded-[1.6rem] border border-dashed border-white/10 bg-white/[0.02] px-6 py-14 text-center">



                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-500/10 text-2xl text-violet-300">

                        ◇

                    </div>



                    <div class="mt-4 font-semibold text-white">

                        {{ __('store.catalog_preparing') }}

                    </div>



                    <div class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">

                        {{ __('store.catalog_preparing_text') }}

                    </div>



                </div>



            @endforelse



        </div>



    </div>



</section>



{{-- ============================================================

    4. CATEGORIES

============================================================ --}}

<section class="border-y border-white/[0.07] bg-white/[0.02]">



    <div class="mx-auto max-w-7xl px-5 py-24 lg:px-8">



        <div class="text-center">



            <p class="text-xs font-bold uppercase tracking-[0.22em] text-pink-400">

                {{ __('store.shop_your_way') }}

            </p>



            <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">

                {{ __('store.categories_title') }}

            </h2>



        </div>



        <div class="mt-10 grid gap-5 md:grid-cols-3">



            <a

                href="{{ route('products.index') }}"

                class="group relative overflow-hidden rounded-3xl border border-cyan-400/10 bg-gradient-to-br from-cyan-500/10 via-[#0C101B] to-[#0C101B] p-7 transition hover:-translate-y-1 hover:border-cyan-400/30"

            >



                <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-cyan-400/20 bg-cyan-400/10 text-xl text-cyan-300">

                    ⚡

                </div>



                <h3 class="mt-6 text-xl font-bold">

                    {{ __('store.direct_topup') }}

                </h3>



                <p class="mt-3 text-sm leading-6 text-slate-500">

                    {{ __('store.direct_topup_text') }}

                </p>



                <div class="mt-7 text-sm font-semibold text-cyan-300">

                    {{ __('store.explore') }} →

                </div>



            </a>



            <a

                href="{{ route('products.index') }}"

                class="group relative overflow-hidden rounded-3xl border border-violet-400/10 bg-gradient-to-br from-violet-500/10 via-[#0C101B] to-[#0C101B] p-7 transition hover:-translate-y-1 hover:border-violet-400/30"

            >



                <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-violet-400/20 bg-violet-400/10 text-xl text-violet-300">

                    ◆

                </div>



                <h3 class="mt-6 text-xl font-bold">

                    {{ __('store.game_credits') }}

                </h3>



                <p class="mt-3 text-sm leading-6 text-slate-500">

                    {{ __('store.game_credits_text') }}

                </p>



                <div class="mt-7 text-sm font-semibold text-violet-300">

                    {{ __('store.explore') }} →

                </div>



            </a>



            <div class="relative overflow-hidden rounded-3xl border border-white/[0.07] bg-gradient-to-br from-pink-500/[0.06] via-[#0C101B] to-[#0C101B] p-7 opacity-80">



                <div class="absolute right-5 top-5 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">

                    {{ __('store.coming_later') }}

                </div>



                <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-pink-400/10 bg-pink-400/[0.06] text-xl text-pink-300">

                    ◈

                </div>



                <h3 class="mt-6 text-xl font-bold">

                    {{ __('store.gift_cards') }}

                </h3>



                <p class="mt-3 text-sm leading-6 text-slate-500">

                    {{ __('store.gift_cards_text') }}

                </p>



            </div>



        </div>



    </div>



</section>



{{-- ============================================================

    5. TRENDING / NO FAKE DISCOUNTS

============================================================ --}}

<section>



    <div class="mx-auto max-w-7xl px-5 py-24 lg:px-8">



        <div class="overflow-hidden rounded-[2rem] border border-white/[0.08] bg-[#0B0F19]">



            <div class="grid lg:grid-cols-[.9fr_1.1fr]">



                <div class="relative overflow-hidden p-8 sm:p-10 lg:p-12">



                    <div class="absolute -left-16 -top-16 h-56 w-56 rounded-full bg-pink-500/10 blur-3xl"></div>



                    <div class="relative">



                        <div class="inline-flex rounded-full border border-pink-400/20 bg-pink-400/10 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-pink-300">

                            {{ __('store.popular_now') }}

                        </div>



                        <h2 class="mt-5 max-w-lg text-3xl font-black tracking-tight sm:text-4xl">

                            {{ __('store.trending_title') }}

                        </h2>



                        <p class="mt-4 max-w-lg text-sm leading-7 text-slate-500">

                            {{ __('store.trending_text') }}

                        </p>



                        <a

                            href="{{ route('products.index') }}"

                            class="mt-7 inline-flex items-center gap-2 text-sm font-bold text-white"

                        >

                            {{ __('store.see_catalog') }}

                            <span class="text-pink-300">→</span>

                        </a>



                    </div>



                </div>



                <div class="grid grid-cols-3 gap-px bg-white/[0.07]">



                    <div class="flex min-h-56 flex-col justify-end bg-gradient-to-b from-amber-500/10 to-[#0B0F19] p-5">



                        <div class="text-4xl font-black text-amber-300/80">

                            P

                        </div>



                        <div class="mt-8 text-sm font-bold">

                            PUBG Mobile

                        </div>



                    </div>



                    <div class="flex min-h-56 flex-col justify-end bg-gradient-to-b from-cyan-500/10 to-[#0B0F19] p-5">



                        <div class="text-4xl font-black text-cyan-300/80">

                            F

                        </div>



                        <div class="mt-8 text-sm font-bold">

                            Free Fire

                        </div>



                    </div>



                    <div class="flex min-h-56 flex-col justify-end bg-gradient-to-b from-pink-500/10 to-[#0B0F19] p-5">



                        <div class="text-4xl font-black text-pink-300/80">

                            M

                        </div>



                        <div class="mt-8 text-sm font-bold">

                            Mobile Legends

                        </div>



                    </div>



                </div>



            </div>



        </div>



    </div>



</section>



{{-- ============================================================

    6. HOW IT WORKS

============================================================ --}}

<section class="border-y border-white/[0.07] bg-white/[0.02]">



    <div class="mx-auto max-w-7xl px-5 py-24 lg:px-8">



        <div class="mx-auto max-w-2xl text-center">



            <p class="text-xs font-bold uppercase tracking-[0.22em] text-violet-400">

                {{ __('store.simple_process') }}

            </p>



            <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">

                {{ __('store.how_it_works') }}

            </h2>



            <p class="mt-4 text-sm leading-7 text-slate-500">

                {{ __('store.how_it_works_text') }}

            </p>



        </div>



        <div class="relative mt-14 grid gap-5 md:grid-cols-4">



            @php

                $steps = [

                    [

                        'number' => '01',

                        'title' => __('store.step_choose_title'),

                        'text' => __('store.step_choose_text'),

                        'color' => 'cyan',

                    ],

                    [

                        'number' => '02',

                        'title' => __('store.step_details_title'),

                        'text' => __('store.step_details_text'),

                        'color' => 'violet',

                    ],

                    [

                        'number' => '03',

                        'title' => __('store.step_payment_title'),

                        'text' => __('store.step_payment_text'),

                        'color' => 'pink',

                    ],

                    [

                        'number' => '04',

                        'title' => __('store.step_delivery_title'),

                        'text' => __('store.step_delivery_text'),

                        'color' => 'emerald',

                    ],

                ];

            @endphp



            @foreach($steps as $step)



                <div class="relative rounded-3xl border border-white/[0.08] bg-[#0C101B] p-7">



                    <div class="text-4xl font-black text-white/[0.08]">

                        {{ $step['number'] }}

                    </div>



                    <h3 class="mt-8 text-lg font-bold">

                        {{ $step['title'] }}

                    </h3>



                    <p class="mt-3 text-sm leading-6 text-slate-500">

                        {{ $step['text'] }}

                    </p>



                </div>



            @endforeach



        </div>



    </div>



</section>



{{-- ============================================================

    7. WHY NEXORA

============================================================ --}}

<section>



    <div class="mx-auto grid max-w-7xl gap-12 px-5 py-24 lg:grid-cols-[.8fr_1.2fr] lg:items-center lg:px-8">



        <div>



            <p class="text-xs font-bold uppercase tracking-[0.22em] text-emerald-400">

                {{ __('store.why_nexora_eyebrow') }}

            </p>



            <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">

                {{ __('store.why_nexora_title') }}

            </h2>



            <p class="mt-5 max-w-lg text-sm leading-7 text-slate-500">

                {{ __('store.why_nexora_text') }}

            </p>



            <a

                href="{{ route('help.index') }}"

                class="mt-7 inline-flex items-center gap-2 text-sm font-bold text-white"

            >

                {{ __('store.learn_more') }}

                <span class="text-emerald-300">→</span>

            </a>



        </div>



        <div class="grid gap-4 sm:grid-cols-2">



            <div class="rounded-3xl border border-white/[0.08] bg-white/[0.025] p-6">



                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyan-400/10 text-cyan-300">

                    ⚡

                </div>



                <div class="mt-5 font-bold">

                    {{ __('store.why_speed_title') }}

                </div>



                <p class="mt-2 text-sm leading-6 text-slate-500">

                    {{ __('store.why_speed_text') }}

                </p>



            </div>



            <div class="rounded-3xl border border-white/[0.08] bg-white/[0.025] p-6">



                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-400/10 text-violet-300">

                    ⌁

                </div>



                <div class="mt-5 font-bold">

                    {{ __('store.why_tracking_title') }}

                </div>



                <p class="mt-2 text-sm leading-6 text-slate-500">

                    {{ __('store.why_tracking_text') }}

                </p>



            </div>



            <div class="rounded-3xl border border-white/[0.08] bg-white/[0.025] p-6">



                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-pink-400/10 text-pink-300">

                    ◇

                </div>



                <div class="mt-5 font-bold">

                    {{ __('store.why_support_title') }}

                </div>



                <p class="mt-2 text-sm leading-6 text-slate-500">

                    {{ __('store.why_support_text') }}

                </p>



            </div>



            <div class="rounded-3xl border border-white/[0.08] bg-white/[0.025] p-6">



                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-400/10 text-emerald-300">

                    ✓

                </div>



                <div class="mt-5 font-bold">

                    {{ __('store.why_company_title') }}

                </div>



                <p class="mt-2 text-sm leading-6 text-slate-500">

                    {{ __('store.why_company_text') }}

                </p>



            </div>



        </div>



    </div>



</section>



{{-- ============================================================

    8. COMPANY / BANK TRUST

============================================================ --}}

<section class="px-5 pb-24 lg:px-8">



    <div class="mx-auto max-w-7xl overflow-hidden rounded-[2rem] border border-white/[0.08] bg-gradient-to-br from-violet-500/[0.07] via-[#0B0F19] to-cyan-500/[0.05]">



        <div class="grid gap-10 p-8 lg:grid-cols-[1fr_auto] lg:items-center lg:p-12">



            <div>



                <div class="text-xs font-bold uppercase tracking-[0.2em] text-violet-300">

                    {{ __('store.registered_business') }}

                </div>



                <h2 class="mt-4 text-2xl font-black sm:text-3xl">

                    {{ config('company.legal_short_name') }}

                </h2>



                <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-500">

                    {{ __('store.company_transparency_text') }}

                </p>



                <div class="mt-7 flex flex-wrap gap-x-8 gap-y-4 text-sm">



                    <div>

                        <div class="text-xs text-slate-600">

                            {{ __('store.tax_id') }}

                        </div>



                        <div class="mt-1 font-semibold text-slate-300">

                            {{ config('company.tax_id') }}

                        </div>

                    </div>



                    <div>

                        <div class="text-xs text-slate-600">

                            {{ __('store.address') }}

                        </div>



                        <div class="mt-1 font-semibold text-slate-300">

                            {{ config('company.legal_address') }}

                        </div>

                    </div>



                    <div>

                        <div class="text-xs text-slate-600">

                            {{ __('store.phone') }}

                        </div>



                        <div class="mt-1 font-semibold text-slate-300">

                            {{ config('company.phone_display') }}

                        </div>

                    </div>



                </div>



            </div>



            <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">



                <a

                    href="{{ route('contact') }}"

                    class="rounded-xl bg-white px-5 py-3 text-center text-sm font-bold text-slate-950 transition hover:bg-slate-200"

                >

                    {{ __('store.contact_us') }}

                </a>



                <a

                    href="{{ route('legal.terms') }}"

                    class="rounded-xl border border-white/10 px-5 py-3 text-center text-sm font-semibold text-slate-300 transition hover:bg-white/5"

                >

                    {{ __('store.view_legal') }}

                </a>



            </div>



        </div>



    </div>



</section>



{{-- ============================================================

    9. FINAL CTA

============================================================ --}}

<section class="border-t border-white/[0.07]">



    <div class="mx-auto max-w-7xl px-5 py-20 text-center lg:px-8">



        <div class="mx-auto max-w-2xl">



            <h2 class="text-3xl font-black tracking-tight sm:text-4xl">

                {{ __('store.final_cta_title') }}

            </h2>



            <p class="mt-4 text-sm leading-7 text-slate-500">

                {{ __('store.final_cta_text') }}

            </p>



            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">



                <a

                    href="{{ route('products.index') }}"

                    class="rounded-2xl bg-gradient-to-r from-cyan-500 via-violet-600 to-pink-500 px-7 py-4 font-bold text-white shadow-xl shadow-violet-900/20 transition hover:-translate-y-0.5"

                >

                    {{ __('store.browse_products') }}

                </a>



                <a

                    href="{{ route('orders.track') }}"

                    class="rounded-2xl border border-white/10 px-7 py-4 font-semibold text-slate-300 transition hover:bg-white/5"

                >

                    {{ __('store.track_an_order') }}

                </a>



            </div>



        </div>



    </div>



</section>



@endsection