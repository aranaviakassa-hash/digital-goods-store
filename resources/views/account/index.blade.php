@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8">

    <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">

        <div>

            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-violet-400">
                Account
            </p>

            <h1 class="mt-3 text-4xl font-black">
                Welcome, {{ $user->name }}
            </h1>

            <p class="mt-3 text-slate-400">
                {{ $user->email }}
            </p>

        </div>

        <div class="flex items-center gap-2 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">

            <span>✓</span>

            Email verified

        </div>

    </div>

    <div class="mt-12">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-2xl font-bold">
                    My Orders
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Your recent purchases and their current status.
                </p>

            </div>

            <a
                href="{{ route('products.index') }}"
                class="rounded-xl bg-violet-600 px-4 py-2 text-sm font-semibold hover:bg-violet-500"
            >
                Shop
            </a>

        </div>

        <div class="mt-6 space-y-4">

            @forelse($orders as $order)

                <a
                    href="{{ route('orders.show', $order) }}"
                    class="block rounded-2xl border border-white/10 bg-slate-900 p-6 transition hover:border-violet-500/40"
                >

                    <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

                        <div>

                            <div class="text-xs text-slate-500">
                                Order number
                            </div>

                            <div class="mt-1 font-mono text-sm font-semibold">
                                {{ $order->order_number }}
                            </div>

                            <div class="mt-3 text-sm text-slate-400">

                                @foreach($order->items as $item)
                                    {{ $item->product_name }}

                                    @if($item->quantity > 1)
                                        × {{ $item->quantity }}
                                    @endif
                                @endforeach

                            </div>

                        </div>

                        <div class="grid grid-cols-3 gap-6 text-sm">

                            <div>

                                <div class="text-xs text-slate-500">
                                    Payment
                                </div>

                                <div class="mt-1 font-medium">
                                    {{ ucfirst($order->payment_status) }}
                                </div>

                            </div>

                            <div>

                                <div class="text-xs text-slate-500">
                                    Delivery
                                </div>

                                <div class="mt-1 font-medium">
                                    {{ ucfirst(str_replace('_', ' ', $order->fulfillment_status)) }}
                                </div>

                            </div>

                            <div>

                                <div class="text-xs text-slate-500">
                                    Total
                                </div>

                                <div class="mt-1 font-bold">
                                    {{ number_format($order->total, 2) }}
                                    {{ $order->currency }}
                                </div>

                            </div>

                        </div>

                    </div>

                </a>

            @empty

                <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-10 text-center">

                    <div class="text-3xl">
                        🎮
                    </div>

                    <h3 class="mt-4 font-semibold">
                        No orders yet
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Your purchases will appear here.
                    </p>

                </div>

            @endforelse

        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>

    </div>

</section>

@endsection