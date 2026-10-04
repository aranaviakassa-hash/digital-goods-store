@extends('layouts.store')

@section('content')
@php
    $copy = match(app()->getLocale()) {
        'az' => [
            'eyebrow' => 'Sifariş statusu',
            'title' => 'Sifarişini izlə',
            'text' => 'Sifariş nömrəsini və sifariş zamanı istifadə etdiyin email ünvanını daxil et.',
            'order' => 'Sifariş nömrəsi',
            'email' => 'Email ünvanı',
            'button' => 'Sifarişi tap',
            'note' => 'Məlumatlar yalnız uyğun sifarişin statusunu göstərmək üçün istifadə olunur.',
        ],
        'ru' => [
            'eyebrow' => 'Статус заказа',
            'title' => 'Отследить заказ',
            'text' => 'Введите номер заказа и email, использованный при оформлении.',
            'order' => 'Номер заказа',
            'email' => 'Email',
            'button' => 'Найти заказ',
            'note' => 'Данные используются только для отображения статуса соответствующего заказа.',
        ],
        default => [
            'eyebrow' => 'Order status',
            'title' => 'Track your order',
            'text' => 'Enter the order number and email address used during checkout.',
            'order' => 'Order number',
            'email' => 'Email address',
            'button' => 'Find order',
            'note' => 'These details are used only to locate and display the matching order status.',
        ],
    };
@endphp

<section class="mx-auto max-w-3xl px-5 py-20 lg:px-8">
    <div class="text-center">
        <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-400">{{ $copy['eyebrow'] }}</p>
        <h1 class="mt-3 text-4xl font-black tracking-tight text-white sm:text-5xl">{{ $copy['title'] }}</h1>
        <p class="mx-auto mt-4 max-w-xl text-sm leading-7 text-slate-400">{{ $copy['text'] }}</p>
    </div>

    <form method="POST" action="{{ route('orders.track.submit') }}" class="pc-panel mx-auto mt-10 max-w-xl space-y-5 rounded-[2rem] p-6 shadow-2xl shadow-black/20 sm:p-8">
        @csrf

        <div>
            <label for="order_number" class="block text-sm font-semibold text-slate-200">{{ $copy['order'] }}</label>
            <input id="order_number" type="text" name="order_number" value="{{ old('order_number') }}" required autocomplete="off" class="mt-2 w-full rounded-xl border border-white/10 bg-[#080a14] px-4 py-3.5 text-white outline-none transition placeholder:text-slate-700 focus:border-cyan-400/50 focus:ring-2 focus:ring-cyan-400/10">
        </div>

        <div>
            <label for="customer_email" class="block text-sm font-semibold text-slate-200">{{ $copy['email'] }}</label>
            <input id="customer_email" type="email" name="customer_email" value="{{ old('customer_email') }}" required autocomplete="email" class="mt-2 w-full rounded-xl border border-white/10 bg-[#080a14] px-4 py-3.5 text-white outline-none transition placeholder:text-slate-700 focus:border-violet-400/50 focus:ring-2 focus:ring-violet-400/10">
        </div>

        @if($errors->any())
            <div class="rounded-xl border border-red-400/20 bg-red-500/10 p-4 text-sm text-red-200">{{ $errors->first() }}</div>
        @endif

        <button class="pc-button w-full rounded-xl px-6 py-3.5 font-bold text-white transition">{{ $copy['button'] }}</button>
        <p class="text-center text-xs leading-5 text-slate-600">{{ $copy['note'] }}</p>
    </form>
</section>
@endsection
