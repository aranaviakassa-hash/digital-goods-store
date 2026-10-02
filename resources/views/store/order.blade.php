@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-5xl px-5 py-16 lg:px-8">

    <div>

        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-violet-400">
            Order
        </p>

        <h1 class="mt-3 break-all text-3xl font-black sm:text-4xl">
            {{ $order->order_number }}
        </h1>

        <p class="mt-3 text-sm text-slate-400">
            Created {{ $order->created_at->format('d M Y, H:i') }}
        </p>

    </div>

    <div class="mt-10 grid gap-8 lg:grid-cols-[1fr_320px]">

        <div class="space-y-6">

            <div class="rounded-2xl border border-white/10 bg-slate-900 p-6">

                <h2 class="text-lg font-semibold">
                    Order progress
                </h2>

                @php
                    $paymentDone = $order->payment_status === 'paid';

                    $securityDone = in_array(
                        $order->fulfillment_status,
                        ['processing', 'fulfilled']
                    );

                    $fulfillmentDone =
                        $order->fulfillment_status === 'fulfilled';
                @endphp

                <div class="mt-7 space-y-6">

                    <div class="flex gap-4">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $paymentDone ? 'bg-emerald-500 text-white' : 'bg-white/10 text-slate-400' }}">
                            {{ $paymentDone ? '✓' : '1' }}
                        </div>

                        <div>

                            <div class="font-medium">
                                Payment verification
                            </div>

                            <div class="mt-1 text-sm text-slate-500">
                                {{ $paymentDone
                                    ? 'Payment confirmed.'
                                    : 'Waiting for payment confirmation.' }}
                            </div>

                        </div>

                    </div>

                    <div class="flex gap-4">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $securityDone ? 'bg-emerald-500 text-white' : 'bg-white/10 text-slate-400' }}">
                            {{ $securityDone ? '✓' : '2' }}
                        </div>

                        <div>

                            <div class="font-medium">
                                Security review
                            </div>

                            <div class="mt-1 text-sm text-slate-500">
                                Order security checks and approval.
                            </div>

                        </div>

                    </div>

                    <div class="flex gap-4">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $fulfillmentDone ? 'bg-emerald-500 text-white' : 'bg-white/10 text-slate-400' }}">
                            {{ $fulfillmentDone ? '✓' : '3' }}
                        </div>

                        <div>

                            <div class="font-medium">
                                Digital delivery
                            </div>

                            <div class="mt-1 text-sm text-slate-500">
                                {{ $fulfillmentDone
                                    ? 'Order fulfilled.'
                                    : 'Fulfillment begins after approval.' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="rounded-2xl border border-white/10 bg-slate-900 p-6">

                <h2 class="text-lg font-semibold">
                    Items
                </h2>

                <div class="mt-5 divide-y divide-white/10">

                    @foreach($order->items as $item)

                        <div class="flex justify-between gap-6 py-5 first:pt-0 last:pb-0">

                            <div>

                                <div class="font-medium">
                                    {{ $item->product_name }}
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    Quantity: {{ $item->quantity }}
                                </div>

                            </div>

                            <div class="font-semibold">
                                {{ number_format($item->total_price, 2) }}
                                {{ $item->currency }}
                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

        <aside>

            <div class="rounded-2xl border border-white/10 bg-slate-900 p-6">

                <h2 class="font-semibold">
                    Summary
                </h2>

                <div class="mt-5 space-y-4 text-sm">

                    <div class="flex justify-between gap-5">

                        <span class="text-slate-500">
                            Customer
                        </span>

                        <span class="text-right">
                            {{ $order->customer_email }}
                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-slate-500">
                            Payment
                        </span>

                        <span>
                            {{ ucfirst($order->payment_status) }}
                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-slate-500">
                            Fulfillment
                        </span>

                        <span>
                            {{ ucfirst(str_replace('_', ' ', $order->fulfillment_status)) }}
                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-slate-500">
                            Order status
                        </span>

                        <span>
                            {{ ucfirst($order->status) }}
                        </span>

                    </div>

                </div>

                <div class="my-6 border-t border-white/10"></div>

                <div class="flex items-center justify-between">

                    <span class="font-semibold">
                        Total
                    </span>

                    <span class="text-2xl font-black">
                        {{ number_format($order->total, 2) }}
                        {{ $order->currency }}
                    </span>

                </div>

                <a
                    href="{{ route('help.index') }}"
                    class="mt-6 block rounded-xl border border-white/10 py-3 text-center text-sm font-medium hover:bg-white/5"
                >
                    Need help?
                </a>

            </div>

        </aside>

    </div>

</section>

@endsection