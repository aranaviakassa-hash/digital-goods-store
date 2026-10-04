@extends('layouts.store')

@section('content')
@php
    $copy = match(app()->getLocale()) {
        'az' => [
            'eyebrow' => 'Bank review rejimi',
            'title' => 'Checkout preview',
            'intro' => 'Bu səhifə yalnız bank və merchant review üçün müştəri axınını göstərir. Heç bir real ödəniş tutulmur və sifariş yaradılmır.',
            'customer' => 'Müştəri məlumatları',
            'name' => 'Ad və soyad',
            'email' => 'Email ünvanı',
            'game' => 'Oyun hesabı',
            'player' => 'Player / User ID',
            'zone' => 'Zone ID',
            'region' => 'Server / Region',
            'summary' => 'Sifariş xülasəsi',
            'product' => 'Məhsul',
            'price' => 'Qiymət',
            'pending' => 'Kommersiya aktivləşməsi gözlənilir',
            'terms' => 'Şərtlər, Refund Policy və Delivery Policy checkout zamanı qəbul ediləcək.',
            'button' => 'Test ödənişini davam etdirmək mümkün deyil',
            'note' => 'ABB acquiring inteqrasiyası və məhsulun kommersiya təsdiqləri tamamlandıqdan sonra real checkout aktiv ediləcək.',
            'back' => 'Məhsula qayıt',
        ],
        'ru' => [
            'eyebrow' => 'Режим банковской проверки',
            'title' => 'Предпросмотр checkout',
            'intro' => 'Эта страница демонстрирует путь клиента только для банковской и merchant-проверки. Реальная оплата не списывается и заказ не создаётся.',
            'customer' => 'Данные клиента', 'name' => 'Имя и фамилия', 'email' => 'Email',
            'game' => 'Игровой аккаунт', 'player' => 'Player / User ID', 'zone' => 'Zone ID', 'region' => 'Сервер / Регион',
            'summary' => 'Сводка заказа', 'product' => 'Товар', 'price' => 'Цена', 'pending' => 'Ожидает коммерческой активации',
            'terms' => 'Условия, политика возврата и доставки будут подтверждаться при реальном checkout.',
            'button' => 'Тестовая оплата недоступна',
            'note' => 'Реальный checkout будет включён после завершения интеграции ABB acquiring и коммерческих согласований товара.',
            'back' => 'Вернуться к товару',
        ],
        default => [
            'eyebrow' => 'Bank review mode', 'title' => 'Checkout preview',
            'intro' => 'This page demonstrates the customer journey for bank and merchant review only. No real payment is charged and no order is created.',
            'customer' => 'Customer information', 'name' => 'Full name', 'email' => 'Email address',
            'game' => 'Game account', 'player' => 'Player / User ID', 'zone' => 'Zone ID', 'region' => 'Server / Region',
            'summary' => 'Order summary', 'product' => 'Product', 'price' => 'Price', 'pending' => 'Pending commercial activation',
            'terms' => 'Terms, Refund Policy and Delivery Policy will be accepted during live checkout.',
            'button' => 'Test payment is not available',
            'note' => 'Live checkout will be enabled after ABB acquiring integration and the required commercial approvals are complete.',
            'back' => 'Back to product',
        ],
    };
@endphp

<section class="mx-auto max-w-6xl px-5 py-16 lg:px-8">
    <div class="mb-8 rounded-2xl border border-amber-400/20 bg-amber-400/[0.055] p-5">
        <div class="text-xs font-black uppercase tracking-[0.2em] text-amber-300">{{ $copy['eyebrow'] }}</div>
        <h1 class="mt-3 text-3xl font-black text-white sm:text-4xl">{{ $copy['title'] }}</h1>
        <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-400">{{ $copy['intro'] }}</p>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1fr_380px]">
        <div class="space-y-6">
            <div class="pc-panel rounded-[1.75rem] p-6">
                <h2 class="text-lg font-bold text-white">{{ $copy['customer'] }}</h2>
                <div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <div><label class="text-sm font-medium text-slate-300">{{ $copy['name'] }}</label><input disabled class="mt-2 w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-slate-500" placeholder="Aysu Zulfugarli"></div>
                    <div><label class="text-sm font-medium text-slate-300">{{ $copy['email'] }}</label><input disabled class="mt-2 w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-slate-500" placeholder="customer@example.com"></div>
                </div>
            </div>

            <div class="pc-panel rounded-[1.75rem] p-6">
                <h2 class="text-lg font-bold text-white">{{ $copy['game'] }}</h2>
                <div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2"><label class="text-sm font-medium text-slate-300">{{ $copy['player'] }}</label><input disabled class="mt-2 w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-slate-500" placeholder="123456789"></div>
                    <div><label class="text-sm font-medium text-slate-300">{{ $copy['zone'] }}</label><input disabled class="mt-2 w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-slate-500" placeholder="Optional"></div>
                    <div><label class="text-sm font-medium text-slate-300">{{ $copy['region'] }}</label><input disabled class="mt-2 w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-slate-500" placeholder="Optional"></div>
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/[0.025] p-5 text-sm leading-6 text-slate-400">
                {{ $copy['terms'] }}
            </div>
        </div>

        <aside class="pc-panel h-fit rounded-[1.75rem] p-6 lg:sticky lg:top-28">
            <div class="h-44 overflow-hidden rounded-2xl border border-white/[0.07] bg-black/20">
                @include('store.partials.product-visual', ['product' => $product])
            </div>
            <h2 class="mt-5 text-lg font-black text-white">{{ $copy['summary'] }}</h2>
            <dl class="mt-5 space-y-4 text-sm">
                <div class="flex justify-between gap-4"><dt class="text-slate-500">{{ $copy['product'] }}</dt><dd class="text-right font-semibold text-slate-200">{{ $product->name }}</dd></div>
                <div class="flex justify-between gap-4 border-t border-white/[0.07] pt-4"><dt class="text-slate-500">{{ $copy['price'] }}</dt><dd class="text-right font-semibold text-amber-300">{{ $product->price !== null ? number_format((float)$product->price, 2).' '.$product->currency : $copy['pending'] }}</dd></div>
            </dl>
            <button disabled class="mt-6 w-full cursor-not-allowed rounded-xl border border-white/10 bg-white/[0.05] px-5 py-3.5 text-sm font-bold text-slate-500">{{ $copy['button'] }}</button>
            <p class="mt-4 text-xs leading-5 text-slate-600">{{ $copy['note'] }}</p>
            <a href="{{ route('products.show', $product) }}" class="mt-5 inline-flex text-sm font-semibold text-cyan-300 hover:text-white">← {{ $copy['back'] }}</a>
        </aside>
    </div>
</section>
@endsection
