@extends('layouts.store')

@section('content')
@php
    $locale = app()->getLocale();
    $copy = match ($locale) {
        'az' => [
            'eyebrow' => 'Əlaqə', 'title' => 'Dəstək və şirkət məlumatları',
            'intro' => 'Sifariş, ödəniş, çatdırılma və hesab məsələləri ilə bağlı PlayCharge ilə əlaqə saxlaya bilərsiniz.',
            'support' => 'Müştəri dəstəyi', 'phone' => 'Telefon', 'whatsapp' => 'WhatsApp', 'email' => 'Email',
            'email_pending' => 'Rəsmi domain email-i aktivləşdirilir', 'hours' => 'Dəstək saatları',
            'business' => 'Şirkət məlumatları', 'legal_name' => 'Hüquqi ad', 'tax_id' => 'VÖEN', 'activity' => 'Fəaliyyət', 'address' => 'Hüquqi ünvan',
            'order_help' => 'Sifariş üzrə dəstək',
            'order_text' => 'Dəstəyə müraciət edərkən sifariş nömrəsini və sifarişdə istifadə olunan email ünvanını qeyd edin. Parol, CVV, birdəfəlik kod və internet bankçılıq giriş məlumatlarını heç vaxt göndərməyin.',
            'track' => 'Sifarişi izlə',
        ],
        'ru' => [
            'eyebrow' => 'Контакты', 'title' => 'Поддержка и информация о компании',
            'intro' => 'Свяжитесь с PlayCharge по вопросам заказов, оплаты, доставки и аккаунта.',
            'support' => 'Поддержка клиентов', 'phone' => 'Телефон', 'whatsapp' => 'WhatsApp', 'email' => 'Email',
            'email_pending' => 'Официальный email домена готовится', 'hours' => 'Часы поддержки',
            'business' => 'Информация о компании', 'legal_name' => 'Юридическое наименование', 'tax_id' => 'ИНН', 'activity' => 'Деятельность', 'address' => 'Юридический адрес',
            'order_help' => 'Поддержка заказа',
            'order_text' => 'При обращении укажите номер заказа и email, использованный при оформлении. Никогда не отправляйте пароль, CVV, одноразовые коды или данные интернет-банкинга.',
            'track' => 'Отследить заказ',
        ],
        default => [
            'eyebrow' => 'Contact', 'title' => 'Support & business information',
            'intro' => 'Contact PlayCharge regarding orders, payments, delivery or account access.',
            'support' => 'Customer support', 'phone' => 'Phone', 'whatsapp' => 'WhatsApp', 'email' => 'Email',
            'email_pending' => 'Official domain email is being activated', 'hours' => 'Support hours',
            'business' => 'Business information', 'legal_name' => 'Legal name', 'tax_id' => 'Tax ID', 'activity' => 'Business activity', 'address' => 'Legal address',
            'order_help' => 'Order support',
            'order_text' => 'When contacting support, include your order number and the email address used for the order. Never send passwords, CVV codes, one-time passwords or internet banking credentials.',
            'track' => 'Track an order',
        ],
    };

    $whatsapp = 'https://wa.me/' . ltrim(preg_replace('/\D+/', '', config('company.whatsapp')), '0');
@endphp

<section class="relative overflow-hidden">
    <div class="absolute inset-0 -z-20 bg-[#070816]"></div>
    <div class="absolute inset-x-0 top-0 -z-10 h-[420px]" style="background:radial-gradient(circle at 15% 0%,rgba(6,182,212,.12),transparent 35%),radial-gradient(circle at 85% 0%,rgba(124,58,237,.15),transparent 35%);"></div>

    <div class="mx-auto max-w-6xl px-5 py-20 lg:px-8 lg:py-24">
        <div class="max-w-3xl">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-400">{{ $copy['eyebrow'] }}</p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">{{ $copy['title'] }}</h1>
            <p class="mt-6 text-base leading-8 text-slate-400">{{ $copy['intro'] }}</p>
        </div>

        <div class="mt-12 grid gap-6 lg:grid-cols-2">
            <div class="pc-panel rounded-[1.75rem] p-7">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-400/10 text-xl text-cyan-300">◇</div>
                <h2 class="mt-6 text-xl font-bold">{{ $copy['support'] }}</h2>
                <div class="mt-6 divide-y divide-white/[0.07]">
                    <div class="py-4">
                        <div class="text-xs uppercase tracking-wider text-slate-600">{{ $copy['phone'] }}</div>
                        <a href="tel:{{ config('company.phone') }}" class="mt-2 block font-semibold text-white hover:text-cyan-300">{{ config('company.phone_display') }}</a>
                    </div>
                    <div class="py-4">
                        <div class="text-xs uppercase tracking-wider text-slate-600">{{ $copy['whatsapp'] }}</div>
                        <a href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex font-semibold text-emerald-300 hover:text-emerald-200">{{ config('company.phone_display') }} <span class="ml-2">↗</span></a>
                    </div>
                    <div class="py-4">
                        <div class="text-xs uppercase tracking-wider text-slate-600">{{ $copy['email'] }}</div>
                        @if(config('company.support_email'))
                            <a href="mailto:{{ config('company.support_email') }}" class="mt-2 block font-semibold text-white hover:text-violet-300">{{ config('company.support_email') }}</a>
                        @else
                            <div class="mt-2 text-sm text-slate-500">{{ $copy['email_pending'] }}</div>
                        @endif
                    </div>
                    <div class="py-4">
                        <div class="text-xs uppercase tracking-wider text-slate-600">{{ $copy['hours'] }}</div>
                        <div class="mt-2 font-semibold text-white">{{ config('company.support_hours') }}</div>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-[1.75rem] border border-violet-400/10 bg-gradient-to-br from-violet-500/[0.07] to-white/[0.02] p-7">
                <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-fuchsia-500/10 blur-3xl"></div>
                <div class="relative">
                    <img src="{{ asset('brand/playcharge-mark.svg') }}" alt="PlayCharge" class="h-12 w-12">
                    <h2 class="mt-6 text-xl font-bold">{{ $copy['business'] }}</h2>
                    <div class="mt-6 divide-y divide-white/[0.07] text-sm">
                        <div class="py-4"><div class="text-slate-600">{{ $copy['legal_name'] }}</div><div class="mt-2 font-semibold text-white">{{ config('company.legal_name') }}</div></div>
                        <div class="py-4"><div class="text-slate-600">{{ $copy['tax_id'] }}</div><div class="mt-2 font-semibold text-white">{{ config('company.tax_id') }}</div></div>
                        <div class="py-4"><div class="text-slate-600">{{ $copy['activity'] }}</div><div class="mt-2 leading-6 text-slate-300">{{ config('company.activity') }}</div></div>
                        <div class="py-4"><div class="text-slate-600">{{ $copy['address'] }}</div><div class="mt-2 leading-6 text-slate-300">{{ config('company.legal_address') }}</div></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 rounded-[1.75rem] border border-amber-400/10 bg-amber-400/[0.035] p-7">
            <div class="grid gap-6 sm:grid-cols-[1fr_auto] sm:items-center">
                <div>
                    <h2 class="font-bold text-white">{{ $copy['order_help'] }}</h2>
                    <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-400">{{ $copy['order_text'] }}</p>
                </div>
                <a href="{{ route('orders.track') }}" class="rounded-xl bg-white px-5 py-3 text-center text-sm font-bold text-slate-950 transition hover:bg-slate-200">{{ $copy['track'] }}</a>
            </div>
        </div>
    </div>
</section>
@endsection
