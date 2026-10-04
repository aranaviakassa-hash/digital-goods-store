<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    @php
        $locale = app()->getLocale();
        $metaDescription = match($locale) {
            'az' => 'PlayCharge — rəqəmsal oyun məhsulları və top-up xidmətləri üçün təhlükəsiz və izlənilən platforma.',
            'ru' => 'PlayCharge — платформа для цифровых игровых товаров и пополнений с защищённым и отслеживаемым процессом.',
            default => 'PlayCharge — digital gaming products and top-up services with a secure, trackable customer journey.',
        };
        $secureCommerce = match($locale) {
            'az' => 'Qorunan rəqəmsal ticarət',
            'ru' => 'Защищённая цифровая торговля',
            default => 'Secure digital commerce',
        };
        $reviewTitle = match($locale) {
            'az' => 'Merchant review rejimi',
            'ru' => 'Режим merchant review',
            default => 'Merchant review mode',
        };
        $reviewText = match($locale) {
            'az' => 'Sayt ABB merchant/acquiring yoxlaması üçün non-transactional preview rejimindədir. Real kart ödənişi aktiv deyil.',
            'ru' => 'Сайт работает в режиме non-transactional preview для merchant/acquiring проверки ABB. Реальные карточные платежи не активны.',
            default => 'This site is in non-transactional preview mode for ABB merchant/acquiring review. Live card payments are not enabled.',
        };
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('company.brand_full_name') }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('brand/playcharge-mark.svg') }}">
    <meta name="theme-color" content="#070816">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen text-white antialiased pc-grid-bg">
@if(!config('company.live_payment_enabled'))
    <div class="border-b border-amber-300/20 bg-amber-300/[0.08]">
        <div class="mx-auto flex max-w-7xl items-start gap-3 px-5 py-2.5 text-[11px] leading-5 text-amber-100/90 lg:px-8 sm:items-center sm:text-xs">
            <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-amber-300/25 bg-amber-300/10 text-[10px] font-black text-amber-200 sm:mt-0">!</span>
            <p><strong class="font-black text-amber-100">{{ $reviewTitle }}:</strong> {{ $reviewText }}</p>
        </div>
    </div>
@endif

<header class="sticky top-0 z-50 border-b border-white/10 bg-[#070816]/85 backdrop-blur-2xl">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-3.5 lg:px-8">
        <a href="{{ route('home') }}" class="group flex items-center gap-3" aria-label="PlayCharge home">
            <img src="{{ asset('brand/playcharge-logo.svg') }}" alt="PlayCharge" class="h-10 w-auto max-w-[190px] transition duration-300 group-hover:scale-[1.015]">
        </a>

        <nav class="hidden items-center gap-7 text-sm text-slate-300 md:flex">
            <a href="{{ route('products.index') }}" class="transition hover:text-cyan-300">{{ __('store.products') }}</a>
            <a href="{{ route('orders.track') }}" class="transition hover:text-violet-300">{{ __('store.track_order') }}</a>
            <a href="{{ route('help.index') }}" class="transition hover:text-fuchsia-300">{{ __('store.help') }}</a>
            <a href="{{ route('contact') }}" class="transition hover:text-blue-300">{{ __('store.support') }}</a>
        </nav>

        <div class="flex items-center gap-3">
            <div class="hidden items-center gap-1 rounded-xl border border-white/10 bg-white/[0.035] p-1 sm:flex">
                @foreach(['az' => 'AZ', 'en' => 'EN', 'ru' => 'RU'] as $localeCode => $label)
                    <a href="{{ route('locale.update', $localeCode) }}" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold transition {{ app()->getLocale() === $localeCode ? 'pc-brand-gradient text-white' : 'text-slate-400 hover:text-white' }}">{{ $label }}</a>
                @endforeach
            </div>

            @auth
                <a href="{{ route('account.index') }}" class="hidden text-sm text-slate-300 transition hover:text-white lg:inline">{{ __('store.my_account') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-xl border border-white/10 bg-white/[0.03] px-4 py-2 text-sm text-slate-200 transition hover:bg-white/[0.08]">{{ __('store.logout') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hidden rounded-xl px-4 py-2 text-sm text-slate-300 transition hover:text-white sm:block">{{ __('store.login') }}</a>
                <a href="{{ route('register') }}" class="pc-button rounded-xl px-4 py-2 text-sm font-semibold text-white transition">{{ __('store.create_account') }}</a>
            @endauth
        </div>
    </div>

    <div class="border-t border-white/5 px-5 py-3 sm:hidden">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4">
            <div class="flex min-w-0 gap-4 overflow-x-auto whitespace-nowrap text-sm text-slate-400 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                <a href="{{ route('products.index') }}">{{ __('store.products') }}</a>
                <a href="{{ route('orders.track') }}">{{ __('store.track_order') }}</a>
                <a href="{{ route('help.index') }}">{{ __('store.help') }}</a>
                <a href="{{ route('contact') }}">{{ __('store.support') }}</a>
            </div>
            <div class="flex shrink-0 items-center gap-1">
                @foreach(['az' => 'AZ', 'en' => 'EN', 'ru' => 'RU'] as $localeCode => $label)
                    <a href="{{ route('locale.update', $localeCode) }}" class="rounded-md px-2 py-1 text-[11px] {{ app()->getLocale() === $localeCode ? 'pc-brand-gradient text-white' : 'text-slate-500' }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>
    </div>
</header>

<main class="min-h-[70vh]">@yield('content')</main>

<footer class="border-t border-white/10 bg-[#070816]/92 backdrop-blur-xl">
    <div class="mx-auto grid max-w-7xl gap-10 px-5 py-14 md:grid-cols-4 lg:px-8">
        <div class="md:col-span-2">
            <img src="{{ asset('brand/playcharge-logo.svg') }}" alt="PlayCharge" class="h-11 w-auto max-w-[210px]">
            <p class="mt-5 max-w-md text-sm leading-6 text-slate-400">{{ __('store.footer_text') }}</p>
            <div class="mt-5 rounded-2xl border border-white/[0.07] bg-white/[0.025] p-4 text-xs leading-5 text-slate-500">
                <div class="font-semibold text-slate-300">{{ __('store.operated_by', ['company' => config('company.legal_short_name')]) }}</div>
                <div class="mt-2">{{ __('store.tax_id') }}: {{ config('company.tax_id') }}</div>
                <div>{{ config('company.legal_address') }}</div>
                <div>{{ __('store.phone') }}: {{ config('company.phone_display') }}</div>
                @if(config('company.support_email'))<div>Email: {{ config('company.support_email') }}</div>@endif
            </div>
        </div>

        <div>
            <h3 class="font-semibold">{{ __('store.customer') }}</h3>
            <div class="mt-4 flex flex-col gap-3 text-sm text-slate-400">
                <a href="{{ route('products.index') }}" class="hover:text-cyan-300">{{ __('store.products') }}</a>
                <a href="{{ route('orders.track') }}" class="hover:text-violet-300">{{ __('store.track_order') }}</a>
                <a href="{{ route('help.index') }}" class="hover:text-fuchsia-300">{{ __('store.help_center') }}</a>
                <a href="{{ route('contact') }}" class="hover:text-blue-300">{{ __('store.support') }}</a>
            </div>
        </div>

        <div>
            <h3 class="font-semibold">{{ __('store.legal') }}</h3>
            <div class="mt-4 flex flex-col gap-3 text-sm text-slate-400">
                <a class="hover:text-white" href="{{ route('legal.terms') }}">{{ __('store.terms') }}</a>
                <a class="hover:text-white" href="{{ route('legal.privacy') }}">{{ __('store.privacy') }}</a>
                <a class="hover:text-white" href="{{ route('legal.refund') }}">{{ __('store.refund') }}</a>
                <a class="hover:text-white" href="{{ route('legal.delivery') }}">{{ __('store.delivery_policy') }}</a>
                <a class="hover:text-white" href="{{ route('legal.security') }}">{{ __('store.fraud_security') }}</a>
            </div>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="mx-auto max-w-7xl px-5 py-6 lg:px-8">
            <p class="max-w-5xl text-[11px] leading-5 text-slate-600">{{ __('store.trademark_notice') }}</p>
            <div class="mt-4 flex flex-col gap-3 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                <span>© {{ date('Y') }} {{ config('company.brand_full_name') }}.</span>
                <span>{{ config('company.public_domain') }} • {{ $secureCommerce }} • AZN</span>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
