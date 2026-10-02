@extends('layouts.store')

@section('content')

<section class="relative overflow-hidden">

    <div class="absolute inset-0 -z-10">

        <div class="absolute left-1/2 top-0 h-[500px] w-[700px] -translate-x-1/2 rounded-full bg-violet-700/20 blur-[130px]"></div>

        <div class="absolute right-0 top-40 h-[300px] w-[300px] rounded-full bg-blue-600/10 blur-[100px]"></div>

    </div>

    <div class="mx-auto max-w-7xl px-5 pb-24 pt-24 text-center lg:px-8 lg:pt-32">

        <div class="mx-auto inline-flex items-center gap-2 rounded-full border border-violet-400/20 bg-violet-400/10 px-4 py-2 text-xs font-medium text-violet-200">

            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>

            {{ __('store.hero_badge') }}

        </div>

        <h1 class="mx-auto mt-8 max-w-4xl text-5xl font-black tracking-tight sm:text-6xl lg:text-7xl">

            {{ __('store.hero_title_1') }}

            <span class="bg-gradient-to-r from-violet-400 to-blue-400 bg-clip-text text-transparent">
                {{ __('store.hero_title_2') }}
            </span>

        </h1>

        <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-slate-400">
            {{ __('store.hero_text') }}
        </p>

        <div class="mt-10 flex flex-col justify-center gap-3 sm:flex-row">

            <a
                href="{{ route('products.index') }}"
                class="rounded-xl bg-violet-600 px-7 py-4 font-semibold shadow-xl shadow-violet-600/20 transition hover:bg-violet-500"
            >
                {{ __('store.browse_products') }}
            </a>

            <a
                href="{{ route('orders.track') }}"
                class="rounded-xl border border-white/10 bg-white/5 px-7 py-4 font-semibold text-slate-200 transition hover:bg-white/10"
            >
                {{ __('store.track_an_order') }}
            </a>

        </div>

        <div class="mx-auto mt-14 grid max-w-3xl grid-cols-1 gap-3 sm:grid-cols-3">

            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">

                <div class="text-2xl">
                    🔒
                </div>

                <div class="mt-3 font-semibold">
                    {{ __('store.secure_payments') }}
                </div>

                <div class="mt-1 text-xs text-slate-500">
                    Server-side verification
                </div>

            </div>

            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">

                <div class="text-2xl">
                    ⚡
                </div>

                <div class="mt-3 font-semibold">
                    {{ __('store.digital_delivery') }}
                </div>

                <div class="mt-1 text-xs text-slate-500">
                    Fast automated processing
                </div>

            </div>

            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">

                <div class="text-2xl">
                    🛡️
                </div>

                <div class="mt-3 font-semibold">
                    {{ __('store.order_protection') }}
                </div>

                <div class="mt-1 text-xs text-slate-500">
                    Security review system
                </div>

            </div>

        </div>

    </div>

</section>

<section class="border-y border-white/10 bg-white/[0.02]">

    <div class="mx-auto max-w-7xl px-5 py-20 lg:px-8">

        <div class="flex items-end justify-between gap-5">

            <div>

                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-violet-400">
                    Store
                </p>

                <h2 class="mt-3 text-3xl font-bold">
                    {{ __('store.popular_products') }}
                </h2>

            </div>

            <a
                href="{{ route('products.index') }}"
                class="hidden text-sm font-semibold text-violet-300 hover:text-violet-200 sm:block"
            >
                {{ __('store.view_all') }} →
            </a>

        </div>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

            @forelse($products as $product)

                <a
                    href="{{ route('products.show', $product) }}"
                    class="group overflow-hidden rounded-2xl border border-white/10 bg-slate-900 transition hover:-translate-y-1 hover:border-violet-500/40 hover:shadow-2xl hover:shadow-violet-900/10"
                >

                    <div class="relative flex h-40 items-center justify-center overflow-hidden bg-gradient-to-br from-violet-700/30 via-slate-900 to-blue-700/20">

                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(139,92,246,0.25),transparent_45%)]"></div>

                        <div class="relative flex h-20 w-20 items-center justify-center rounded-2xl border border-white/10 bg-white/10 text-3xl font-black backdrop-blur">
                            {{ strtoupper(substr($product->name, 0, 1)) }}
                        </div>

                    </div>

                    <div class="p-6">

                        <div class="flex items-center justify-between gap-4">

                            <span class="rounded-full bg-violet-500/10 px-3 py-1 text-[11px] font-medium text-violet-300">
                                {{ $product->category }}
                            </span>

                            <span class="text-xs text-emerald-400">
                                {{ __('store.available') }}
                            </span>

                        </div>

                        <h3 class="mt-4 text-lg font-semibold transition group-hover:text-violet-300">
                            {{ $product->name }}
                        </h3>

                        <div class="mt-5 flex items-center justify-between">

                            <div>

                                <div class="text-xs text-slate-500">
                                    {{ __('store.from') }}
                                </div>

                                <div class="mt-1 text-xl font-bold">
                                    {{ number_format($product->price, 2) }}
                                    {{ $product->currency }}
                                </div>

                            </div>

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/5 text-lg transition group-hover:bg-violet-600">
                                →
                            </div>

                        </div>

                    </div>

                </a>

            @empty

                <div class="col-span-full rounded-2xl border border-white/10 bg-white/[0.03] p-10 text-center text-slate-400">
                    No products are currently available.
                </div>

            @endforelse

        </div>

    </div>

</section>

<section>

    <div class="mx-auto max-w-7xl px-5 py-24 lg:px-8">

        <div class="text-center">

            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-violet-400">
                Simple process
            </p>

            <h2 class="mt-3 text-3xl font-bold">
                {{ __('store.how_it_works') }}
            </h2>

        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-3">

            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-7">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-600 font-bold">
                    1
                </div>

                <h3 class="mt-5 text-lg font-semibold">
                    {{ __('store.choose_product') }}
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-400">
                    Select an available digital product and review
                    its price and delivery details.
                </p>

            </div>

            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-7">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-600 font-bold">
                    2
                </div>

                <h3 class="mt-5 text-lg font-semibold">
                    {{ __('store.complete_payment') }}
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-400">
                    Payment is verified securely before an order
                    is approved for fulfillment.
                </p>

            </div>

            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-7">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-600 font-bold">
                    3
                </div>

                <h3 class="mt-5 text-lg font-semibold">
                    {{ __('store.receive_order') }}
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-400">
                    Approved orders proceed to digital fulfillment
                    and can be tracked using the order number.
                </p>

            </div>

        </div>

    </div>

</section>

@endsection