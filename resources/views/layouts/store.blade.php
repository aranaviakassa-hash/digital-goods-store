<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    @php
        $metaDescription = match(app()->getLocale()) {
            'az' => 'PlayCharge — rəqəmsal oyun məhsulları və top-up xidmətləri üçün təhlükəsiz və izlənilən platforma.',
            'ru' => 'PlayCharge — платформа для цифровых игровых товаров и пополнений с защищённым и отслеживаемым процессом.',
            default => 'PlayCharge — digital gaming products and top-up services with a secure, trackable customer journey.',
        };
        $secureCommerce = match(app()->getLocale()) {
            'az' => 'Qorunan rəqəmsal ticarət',
            'ru' => 'Защищённая цифровая торговля',
            default => 'Secure digital commerce',
        };
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('company.brand_full_name') }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('brand/playcharge-mark.svg') }}">
    <meta name="theme-color" content="#05070d">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#05070d] text-white antialiased pc-shell">
@if(!config('company.live_payment_enabled'))
    <div class="relative z-[60] border-b border-blue-400/15 bg-[#081326]">
        <div class="mx-auto flex min-h-10 max-w-7xl items-center justify-center gap-2 px-4 py-2 text-center text-[11px] font-semibold leading-5 text-blue-100 sm:text-xs">
            <span class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-blue-300/20 bg-blue-300/10 text-[10px]">◆</span>
            <span><strong>{{ __('store.bank_review_preview') }}</strong> — {{ __('store.bank_review_preview_text') }}</span>
        </div>
    </div>
@endif

<header class="sticky top-0 z-50 border-b border-white/[0.07] bg-[#05070d]/88 backdrop-blur-2xl">
    <div class="pc-glow-line"></div>
    <div class="mx-auto flex h-[72px] max-w-7xl items-center justify-between gap-4 px-5 lg:px-8">
        <a href="{{ route('home') }}" class="group flex min-h-12 items-center" aria-label="PlayCharge">
            <img src="{{ asset('brand/playcharge-logo.svg') }}" alt="PlayCharge" class="h-9 w-auto max-w-[185px] transition duration-300 group-hover:brightness-125">
        </a>

        <nav class="hidden items-center gap-1 md:flex" aria-label="Primary navigation">
            @foreach([
                [route('products.index'), __('store.products')],
                [route('orders.track'), __('store.track_order')],
                [route('help.index'), __('store.help')],
                [route('contact'), __('store.support')],
            ] as [$href, $label])
                <a href="{{ $href }}" class="inline-flex min-h-11 items-center rounded-xl px-4 text-sm font-semibold text-slate-400 transition hover:bg-white/[0.045] hover:text-white">{{ $label }}</a>
            @endforeach
        </nav>

        <div class="flex items-center gap-2">
            <div class="hidden items-center rounded-xl border border-white/[0.08] bg-white/[0.025] p-1 lg:flex">
                @foreach(['az' => 'AZ', 'en' => 'EN', 'ru' => 'RU'] as $locale => $label)
                    <a href="{{ route('locale.update', $locale) }}" class="inline-flex min-h-9 items-center rounded-lg px-2.5 text-[11px] font-black tracking-wider transition {{ app()->getLocale() === $locale ? 'bg-white text-[#05070d]' : 'text-slate-500 hover:text-white' }}">{{ $label }}</a>
                @endforeach
            </div>

            @auth
                <a href="{{ route('account.index') }}" class="hidden min-h-11 items-center rounded-xl px-4 text-sm font-semibold text-slate-300 transition hover:bg-white/[0.04] hover:text-white sm:inline-flex">{{ __('store.my_account') }}</a>
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button type="submit" class="inline-flex min-h-11 items-center rounded-xl border border-white/[0.09] bg-white/[0.03] px-4 text-sm font-semibold text-slate-200 transition hover:bg-white/[0.07]">{{ __('store.logout') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hidden min-h-11 items-center rounded-xl px-4 text-sm font-semibold text-slate-300 transition hover:text-white lg:inline-flex">{{ __('store.login') }}</a>
                <a href="{{ route('register') }}" class="pc-button hidden min-h-11 items-center rounded-xl px-4 text-sm font-bold text-white transition sm:inline-flex">{{ __('store.create_account') }}</a>
            @endauth

            <button type="button" data-mobile-open aria-expanded="false" aria-controls="mobile-navigation" class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-white/[0.09] bg-white/[0.035] text-white md:hidden" aria-label="Menu">
                <span class="grid gap-1.5">
                    <span class="h-px w-5 bg-current"></span>
                    <span class="h-px w-5 bg-current"></span>
                    <span class="h-px w-5 bg-current"></span>
                </span>
            </button>
        </div>
    </div>
</header>

<div id="mobile-navigation" data-mobile-drawer data-open="false" class="pc-mobile-drawer fixed inset-0 z-[80] md:hidden">
    <button type="button" data-mobile-close class="absolute inset-0 bg-black/70 backdrop-blur-sm" aria-label="Close"></button>
    <aside class="pc-mobile-panel absolute inset-y-0 right-0 flex w-[min(92vw,390px)] flex-col border-l border-white/10 bg-[#080c15] shadow-2xl">
        <div class="flex h-[76px] items-center justify-between border-b border-white/[0.07] px-5">
            <img src="{{ asset('brand/playcharge-logo.svg') }}" alt="PlayCharge" class="h-8 w-auto max-w-[170px]">
            <button type="button" data-mobile-close class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-white/10 bg-white/[0.035] text-2xl text-slate-300" aria-label="Close">×</button>
        </div>

        <nav class="flex-1 overflow-y-auto p-5" aria-label="Mobile navigation">
            <div class="space-y-2">
                @foreach([
                    [route('products.index'), __('store.products'), '01'],
                    [route('orders.track'), __('store.track_order'), '02'],
                    [route('help.index'), __('store.help'), '03'],
                    [route('contact'), __('store.support'), '04'],
                ] as [$href, $label, $number])
                    <a href="{{ $href }}" class="group flex min-h-16 items-center justify-between rounded-2xl border border-white/[0.07] bg-white/[0.025] px-5 text-base font-bold text-white transition hover:border-blue-400/30 hover:bg-blue-400/[0.07]">
                        <span>{{ $label }}</span><span class="text-[10px] font-black tracking-widest text-slate-600 group-hover:text-blue-300">{{ $number }}</span>
                    </a>
                @endforeach
            </div>

            <div class="mt-7 border-t border-white/[0.07] pt-6">
                <div class="flex items-center gap-2">
                    @foreach(['az' => 'AZ', 'en' => 'EN', 'ru' => 'RU'] as $locale => $label)
                        <a href="{{ route('locale.update', $locale) }}" class="inline-flex min-h-12 flex-1 items-center justify-center rounded-xl border text-xs font-black tracking-wider transition {{ app()->getLocale() === $locale ? 'border-blue-400/40 bg-blue-500/15 text-blue-100' : 'border-white/[0.07] bg-white/[0.02] text-slate-500' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
        </nav>

        <div class="border-t border-white/[0.07] p-5">
            @auth
                <a href="{{ route('account.index') }}" class="pc-button flex min-h-12 items-center justify-center rounded-xl px-5 text-sm font-bold text-white">{{ __('store.my_account') }}</a>
            @else
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('login') }}" class="flex min-h-12 items-center justify-center rounded-xl border border-white/10 bg-white/[0.03] text-sm font-bold text-slate-200">{{ __('store.login') }}</a>
                    <a href="{{ route('register') }}" class="pc-button flex min-h-12 items-center justify-center rounded-xl px-4 text-sm font-bold text-white">{{ __('store.create_account') }}</a>
                </div>
            @endauth
        </div>
    </aside>
</div>

<main class="min-h-[70vh]">@yield('content')</main>

<footer class="border-t border-white/[0.08] bg-[#05070d]">
    <div class="mx-auto max-w-7xl px-5 py-14 lg:px-8 lg:py-16">
        <div class="grid gap-10 lg:grid-cols-[1.25fr_.75fr_.75fr]">
            <div>
                <img src="{{ asset('brand/playcharge-logo.svg') }}" alt="PlayCharge" class="h-10 w-auto max-w-[200px]">
                <p class="mt-5 max-w-lg text-sm leading-7 text-slate-500">{{ __('store.footer_text') }}</p>

                <div class="pc-panel mt-7 max-w-2xl rounded-2xl p-5">
                    <div class="text-sm font-black text-white">{{ config('company.legal_short_name') }}</div>
                    <div class="mt-3 grid gap-2 text-xs leading-5 text-slate-500 sm:grid-cols-2">
                        <div>{{ __('store.tax_id') }}: <span class="text-slate-300">{{ config('company.tax_id') }}</span></div>
                        <div>{{ __('store.phone') }}: <span class="text-slate-300">{{ config('company.phone_display') }}</span></div>
                        <div class="sm:col-span-2">{{ __('store.address') }}: <span class="text-slate-300">{{ config('company.legal_address') }}</span></div>
                        @if(config('company.support_email'))
                            <div class="sm:col-span-2">Email: <span class="text-slate-300">{{ config('company.support_email') }}</span></div>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <h3 class="pc-kicker text-slate-500">{{ __('store.customer') }}</h3>
                <div class="mt-5 flex flex-col gap-3 text-sm font-semibold text-slate-400">
                    <a href="{{ route('products.index') }}" class="hover:text-white">{{ __('store.products') }}</a>
                    <a href="{{ route('orders.track') }}" class="hover:text-white">{{ __('store.track_order') }}</a>
                    <a href="{{ route('help.index') }}" class="hover:text-white">{{ __('store.help_center') }}</a>
                    <a href="{{ route('contact') }}" class="hover:text-white">{{ __('store.support') }}</a>
                </div>
            </div>

            <div>
                <h3 class="pc-kicker text-slate-500">{{ __('store.legal') }}</h3>
                <div class="mt-5 flex flex-col gap-3 text-sm font-semibold text-slate-400">
                    <a href="{{ route('legal.terms') }}" class="hover:text-white">{{ __('store.terms') }}</a>
                    <a href="{{ route('legal.privacy') }}" class="hover:text-white">{{ __('store.privacy') }}</a>
                    <a href="{{ route('legal.refund') }}" class="hover:text-white">{{ __('store.refund') }}</a>
                    <a href="{{ route('legal.delivery') }}" class="hover:text-white">{{ __('store.delivery_policy') }}</a>
                    <a href="{{ route('legal.security') }}" class="hover:text-white">{{ __('store.fraud_security') }}</a>
                </div>
            </div>
        </div>

        <div class="mt-12 border-t border-white/[0.07] pt-7">
            <p class="max-w-5xl text-[11px] leading-5 text-slate-600">{{ __('store.trademark_notice') }}</p>
            <div class="mt-5 flex flex-col gap-3 text-xs text-slate-600 sm:flex-row sm:items-center sm:justify-between">
                <span>© {{ date('Y') }} {{ config('company.brand_full_name') }}.</span>
                <span>{{ config('company.public_domain') }} • {{ $secureCommerce }} • AZN</span>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
