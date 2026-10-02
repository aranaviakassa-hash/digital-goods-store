@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8">

    <div class="max-w-2xl">

        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-violet-400">
            Marketplace
        </p>

        <h1 class="mt-3 text-4xl font-black">
            Digital Products
        </h1>

        <p class="mt-4 leading-7 text-slate-400">
            Browse currently available gaming top-ups and digital products.
        </p>

    </div>

    <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

        @forelse($products as $product)

            <a
                href="{{ route('products.show', $product) }}"
                class="group overflow-hidden rounded-2xl border border-white/10 bg-slate-900 transition hover:-translate-y-1 hover:border-violet-500/40"
            >

                <div class="relative flex h-40 items-center justify-center bg-gradient-to-br from-violet-700/30 via-slate-900 to-blue-700/20">

                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl border border-white/10 bg-white/10 text-3xl font-black">
                        {{ strtoupper(substr($product->name, 0, 1)) }}
                    </div>

                </div>

                <div class="p-6">

                    <div class="flex items-center justify-between">

                        <span class="rounded-full bg-violet-500/10 px-3 py-1 text-xs text-violet-300">
                            {{ $product->category }}
                        </span>

                        <span class="text-xs text-emerald-400">
                            Available
                        </span>

                    </div>

                    <h2 class="mt-4 text-lg font-semibold">
                        {{ $product->name }}
                    </h2>

                    <div class="mt-6 flex items-center justify-between">

                        <strong class="text-xl">
                            {{ number_format($product->price, 2) }}
                            {{ $product->currency }}
                        </strong>

                        <span class="rounded-xl bg-white/5 px-4 py-2 text-sm group-hover:bg-violet-600">
                            View
                        </span>

                    </div>

                </div>

            </a>

        @empty

            <div class="col-span-full rounded-2xl border border-white/10 bg-white/[0.03] p-10 text-center text-slate-400">
                No products available.
            </div>

        @endforelse

    </div>

    <div class="mt-10 text-slate-300">
        {{ $products->links() }}
    </div>

</section>

@endsection