@extends('layouts.store')

@section('content')
@php
    $copy = match(app()->getLocale()) {
        'az' => [
            'account' => 'Hesab', 'welcome' => 'Xoş gəldin', 'verified' => 'Email təsdiqlənib',
            'orders' => 'Sifarişlərim', 'orders_text' => 'Son sifarişlərin və cari statusları.', 'shop' => 'Məhsullara bax',
            'order_number' => 'Sifariş nömrəsi', 'payment' => 'Ödəniş', 'delivery' => 'Çatdırılma', 'total' => 'Cəmi',
            'empty' => 'Hələ sifariş yoxdur', 'empty_text' => 'Sifarişlərin burada görünəcək.',
        ],
        'ru' => [
            'account' => 'Аккаунт', 'welcome' => 'Добро пожаловать', 'verified' => 'Email подтверждён',
            'orders' => 'Мои заказы', 'orders_text' => 'Последние заказы и их текущий статус.', 'shop' => 'Смотреть товары',
            'order_number' => 'Номер заказа', 'payment' => 'Оплата', 'delivery' => 'Доставка', 'total' => 'Итого',
            'empty' => 'Заказов пока нет', 'empty_text' => 'Ваши заказы появятся здесь.',
        ],
        default => [
            'account' => 'Account', 'welcome' => 'Welcome', 'verified' => 'Email verified',
            'orders' => 'My Orders', 'orders_text' => 'Your recent orders and their current status.', 'shop' => 'Browse products',
            'order_number' => 'Order number', 'payment' => 'Payment', 'delivery' => 'Delivery', 'total' => 'Total',
            'empty' => 'No orders yet', 'empty_text' => 'Your orders will appear here.',
        ],
    };
@endphp

<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-violet-400">{{ $copy['account'] }}</p>
            <h1 class="mt-3 text-4xl font-black tracking-tight">{{ $copy['welcome'] }}, {{ $user->name }}</h1>
            <p class="mt-3 text-slate-400">{{ $user->email }}</p>
        </div>
        <div class="flex items-center gap-2 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-300">
            <span>✓</span>{{ $copy['verified'] }}
        </div>
    </div>

    <div class="mt-12">
        <div class="flex items-center justify-between gap-5">
            <div>
                <h2 class="text-2xl font-black">{{ $copy['orders'] }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $copy['orders_text'] }}</p>
            </div>
            <a href="{{ route('products.index') }}" class="pc-button rounded-xl px-4 py-2 text-sm font-bold text-white">{{ $copy['shop'] }}</a>
        </div>

        <div class="mt-6 space-y-4">
            @forelse($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="pc-panel block rounded-2xl p-6 transition hover:-translate-y-0.5 hover:border-violet-400/30">
                    <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                        <div>
                            <div class="text-xs text-slate-500">{{ $copy['order_number'] }}</div>
                            <div class="mt-1 font-mono text-sm font-semibold text-white">{{ $order->order_number }}</div>
                            <div class="mt-3 text-sm text-slate-400">
                                @foreach($order->items as $item)
                                    {{ $item->product_name }}@if($item->quantity > 1) × {{ $item->quantity }}@endif
                                @endforeach
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-6 text-sm">
                            <div><div class="text-xs text-slate-500">{{ $copy['payment'] }}</div><div class="mt-1 font-medium text-slate-200">{{ ucfirst($order->payment_status) }}</div></div>
                            <div><div class="text-xs text-slate-500">{{ $copy['delivery'] }}</div><div class="mt-1 font-medium text-slate-200">{{ ucfirst(str_replace('_', ' ', $order->fulfillment_status)) }}</div></div>
                            <div><div class="text-xs text-slate-500">{{ $copy['total'] }}</div><div class="mt-1 font-bold text-white">{{ number_format($order->total, 2) }} {{ $order->currency }}</div></div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="pc-panel rounded-2xl p-10 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-500/10 text-2xl">◇</div>
                    <h3 class="mt-4 font-bold text-white">{{ $copy['empty'] }}</h3>
                    <p class="mt-2 text-sm text-slate-500">{{ $copy['empty_text'] }}</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $orders->links() }}</div>
    </div>
</section>
@endsection
