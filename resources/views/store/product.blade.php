@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8">

    <div class="grid gap-10 lg:grid-cols-2">

        <div class="flex min-h-[420px] items-center justify-center overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-violet-700/30 via-slate-900 to-blue-700/20">

            <div class="flex h-36 w-36 items-center justify-center rounded-3xl border border-white/10 bg-white/10 text-6xl font-black shadow-2xl backdrop-blur">
                {{ strtoupper(substr($product->name, 0, 1)) }}
            </div>

        </div>

        <div class="flex flex-col justify-center">

            <div class="flex items-center gap-3">

                <span class="rounded-full bg-violet-500/10 px-3 py-1 text-xs font-medium text-violet-300">
                    {{ $product->category }}
                </span>

                <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-300">
                    Available
                </span>

            </div>

            <h1 class="mt-6 text-4xl font-black sm:text-5xl">
                {{ $product->name }}
            </h1>

            @if($product->description)

                <p class="mt-5 max-w-xl leading-7 text-slate-400">
                    {{ $product->description }}
                </p>

            @else

                <p class="mt-5 max-w-xl leading-7 text-slate-400">
                    Secure digital gaming product processed after
                    successful payment verification.
                </p>

            @endif

            <div class="mt-8">

                <div class="text-sm text-slate-500">
                    Price
                </div>

                <div class="mt-1 text-4xl font-black">
                    {{ number_format($product->price, 2) }}
                    {{ $product->currency }}
                </div>

            </div>

            <div class="mt-8 grid gap-3 sm:grid-cols-2">

                <div class="rounded-xl border border-white/10 bg-white/[0.03] p-4">

                    <div class="text-xs text-slate-500">
                        Delivery
                    </div>

                    <div class="mt-1 text-sm font-medium">
                        Digital fulfillment
                    </div>

                </div>

                <div class="rounded-xl border border-white/10 bg-white/[0.03] p-4">

                    <div class="text-xs text-slate-500">
                        Security
                    </div>

                    <div class="mt-1 text-sm font-medium">
                        Payment verified
                    </div>

                </div>

            </div>

            <div class="mt-5 rounded-xl border border-amber-400/20 bg-amber-400/5 p-4 text-sm leading-6 text-amber-100/80">

                Please verify that the selected product is correct
                before payment. Digital products may become
                irreversible after successful fulfillment.

            </div>

            <a
                href="{{ route('checkout.show', $product) }}"
                class="mt-7 flex items-center justify-center rounded-xl bg-violet-600 px-6 py-4 font-semibold shadow-xl shadow-violet-600/20 transition hover:bg-violet-500"
            >
                Continue to Checkout
            </a>

        </div>

    </div>

</section>

@endsection