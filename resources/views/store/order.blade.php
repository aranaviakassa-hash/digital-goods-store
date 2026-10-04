@extends('layouts.store')

@section('content')
@php
    $paymentDone = $order->payment_status === 'paid';
    $securityDone = in_array($order->fulfillment_status, ['processing', 'fulfilled']);
    $fulfillmentDone = $order->fulfillment_status === 'fulfilled';

    $copy = match(app()->getLocale()) {
        'az' => [
            'order' => 'Sifariş', 'created' => 'Yaradılıb', 'progress' => 'Sifariş gedişatı',
            'payment' => 'Ödəniş yoxlaması', 'payment_done' => 'Ödəniş təsdiqlənib.', 'payment_wait' => 'Ödəniş təsdiqi gözlənilir.',
            'security' => 'Təhlükəsizlik yoxlaması', 'security_text' => 'Sifariş təhlükəsizlik və uyğunluq yoxlamasından keçir.',
            'delivery' => 'Rəqəmsal çatdırılma', 'fulfilled' => 'Sifariş fulfillment olunub.', 'fulfillment_wait' => 'Təsdiqdən sonra fulfillment başlayır.',
            'items' => 'Məhsullar', 'quantity' => 'Miqdar', 'summary' => 'Xülasə', 'customer' => 'Müştəri',
            'fulfillment' => 'Fulfillment', 'status' => 'Sifariş statusu', 'total' => 'Cəmi', 'help' => 'Kömək lazımdır?',
        ],
        'ru' => [
            'order' => 'Заказ', 'created' => 'Создан', 'progress' => 'Ход заказа',
            'payment' => 'Проверка оплаты', 'payment_done' => 'Оплата подтверждена.', 'payment_wait' => 'Ожидается подтверждение оплаты.',
            'security' => 'Проверка безопасности', 'security_text' => 'Заказ проходит проверку безопасности и соответствия.',
            'delivery' => 'Цифровая доставка', 'fulfilled' => 'Заказ исполнен.', 'fulfillment_wait' => 'Исполнение начинается после подтверждения.',
            'items' => 'Товары', 'quantity' => 'Количество', 'summary' => 'Сводка', 'customer' => 'Клиент',
            'fulfillment' => 'Исполнение', 'status' => 'Статус заказа', 'total' => 'Итого', 'help' => 'Нужна помощь?',
        ],
        default => [
            'order' => 'Order', 'created' => 'Created', 'progress' => 'Order progress',
            'payment' => 'Payment verification', 'payment_done' => 'Payment confirmed.', 'payment_wait' => 'Waiting for payment confirmation.',
            'security' => 'Security review', 'security_text' => 'Order security and eligibility checks are in progress.',
            'delivery' => 'Digital delivery', 'fulfilled' => 'Order fulfilled.', 'fulfillment_wait' => 'Fulfillment begins after approval.',
            'items' => 'Items', 'quantity' => 'Quantity', 'summary' => 'Summary', 'customer' => 'Customer',
            'fulfillment' => 'Fulfillment', 'status' => 'Order status', 'total' => 'Total', 'help' => 'Need help?',
        ],
    };
@endphp

<section class="mx-auto max-w-5xl px-5 py-16 lg:px-8">
    <div>
        <p class="text-xs font-bold uppercase tracking-[0.22em] text-violet-400">{{ $copy['order'] }}</p>
        <h1 class="mt-3 break-all text-3xl font-black tracking-tight sm:text-4xl">{{ $order->order_number }}</h1>
        <p class="mt-3 text-sm text-slate-500">{{ $copy['created'] }} {{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>

    <div class="mt-10 grid gap-8 lg:grid-cols-[1fr_330px]">
        <div class="space-y-6">
            <div class="pc-panel rounded-[1.75rem] p-6">
                <h2 class="text-lg font-bold">{{ $copy['progress'] }}</h2>
                <div class="mt-7 space-y-6">
                    @foreach([
                        [$paymentDone, '1', $copy['payment'], $paymentDone ? $copy['payment_done'] : $copy['payment_wait']],
                        [$securityDone, '2', $copy['security'], $copy['security_text']],
                        [$fulfillmentDone, '3', $copy['delivery'], $fulfillmentDone ? $copy['fulfilled'] : $copy['fulfillment_wait']],
                    ] as [$done, $number, $title, $text])
                        <div class="flex gap-4">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $done ? 'bg-emerald-500 text-white' : 'border border-white/10 bg-white/[0.04] text-slate-500' }}">
                                {{ $done ? '✓' : $number }}
                            </div>
                            <div>
                                <div class="font-semibold text-white">{{ $title }}</div>
                                <div class="mt-1 text-sm leading-6 text-slate-500">{{ $text }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pc-panel rounded-[1.75rem] p-6">
                <h2 class="text-lg font-bold">{{ $copy['items'] }}</h2>
                <div class="mt-5 divide-y divide-white/10">
                    @foreach($order->items as $item)
                        <div class="flex justify-between gap-6 py-5 first:pt-0 last:pb-0">
                            <div>
                                <div class="font-semibold text-white">{{ $item->product_name }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ $copy['quantity'] }}: {{ $item->quantity }}</div>
                            </div>
                            <div class="font-bold text-white">{{ number_format($item->total_price, 2) }} {{ $item->currency }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <aside>
            <div class="pc-panel rounded-[1.75rem] p-6 lg:sticky lg:top-28">
                <h2 class="font-bold">{{ $copy['summary'] }}</h2>
                <div class="mt-5 space-y-4 text-sm">
                    <div class="flex justify-between gap-5"><span class="text-slate-500">{{ $copy['customer'] }}</span><span class="break-all text-right text-slate-200">{{ $order->customer_email }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">{{ $copy['payment'] }}</span><span class="text-slate-200">{{ ucfirst($order->payment_status) }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">{{ $copy['fulfillment'] }}</span><span class="text-slate-200">{{ ucfirst(str_replace('_', ' ', $order->fulfillment_status)) }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">{{ $copy['status'] }}</span><span class="text-slate-200">{{ ucfirst($order->status) }}</span></div>
                </div>
                <div class="my-6 border-t border-white/10"></div>
                <div class="flex items-center justify-between gap-4"><span class="font-semibold">{{ $copy['total'] }}</span><span class="text-2xl font-black">{{ number_format($order->total, 2) }} {{ $order->currency }}</span></div>
                <a href="{{ route('help.index') }}" class="mt-6 block rounded-xl border border-white/10 py-3 text-center text-sm font-semibold transition hover:border-violet-400/30 hover:bg-white/[0.04]">{{ $copy['help'] }}</a>
            </div>
        </aside>
    </div>
</section>
@endsection
