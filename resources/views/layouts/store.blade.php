<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $title ?? config('company.brand_full_name') }}
    </title>

    <meta
        name="description"
        content="PlayCharge — rəqəmsal oyun məhsulları və top-up xidmətləri üçün təhlükəsiz onlayn mağaza."
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="min-h-screen text-white antialiased pc-grid-bg">

<header class="sticky top-0 z-50 border-b border-white/10 bg-[#070816]/85 backdrop-blur-2xl">

    <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">

        <a
            href="{{ route('home') }}"
            class="group flex items-center gap-3"
        >
            <div class="pc-brand-gradient pc-glow flex h-11 w-11 items-center justify-center rounded-2xl font-black text-white transition duration-300 group-hover:scale-105">
                P
            </div>

            <div>
                <div class="pc-brand-text text-lg font-black tracking-tight">
                    {{ config('company.brand_name') }}
                </div>

                <div class="text-[10px] uppercase tracking-[0.24em] text-slate-500">
                    Digital Gaming Store
                </div>
            </div>
        </a>

        <nav class="hidden items-center gap-7 text-sm text-slate-300 md:flex">

            <a
                href="{{ route('products.index') }}"
                class="transition hover:text-cyan-300"
            >
                {{ __('store.products') }}
            </a>

            <a
                href="{{ route('orders.track') }}"
                class="transition hover:text-violet-300"
            >
                {{ __('store.track_order') }}
            </a>

            <a
                href="{{ route('help.index') }}"
                class="transition hover:text-fuchsia-300"
            >
                {{ __('store.help') }}
            </a>

            <a
                href="{{ route('contact') }}"
                class="transition hover:text-blue-300"
            >
                {{ __('store.support') }}
            </a>

        </nav>

        <div class="flex items-center gap-3">

            <div class="hidden items-center gap-1 rounded-xl border border-white/10 bg-white/[0.035] p-1 sm:flex">

                <a
                    href="{{ route('locale.update', 'az') }}"
                    class="rounded-lg px-2.5 py-1.5 text-xs font-semibold transition
                    {{ app()->getLocale() === 'az'
                        ? 'pc-brand-gradient text-white'
                        : 'text-slate-400 hover:text-white' }}"
                >
                    AZ
                </a>

                <a
                    href="{{ route('locale.update', 'en') }}"
                    class="rounded-lg px-2.5 py-1.5 text-xs font-semibold transition
                    {{ app()->getLocale() === 'en'
                        ? 'pc-brand-gradient text-white'
                        : 'text-slate-400 hover:text-white' }}"
                >
                    EN
                </a>

                <a
                    href="{{ route('locale.update', 'ru') }}"
                    class="rounded-lg px-2.5 py-1.5 text-xs font-semibold transition
                    {{ app()->getLocale() === 'ru'
                        ? 'pc-brand-gradient text-white'
                        : 'text-slate-400 hover:text-white' }}"
                >
                    RU
                </a>

            </div>

            @auth

                <a
                    href="{{ route('account.index') }}"
                    class="hidden text-sm text-slate-300 transition hover:text-white lg:inline"
                >
                    {{ __('store.my_account') }}
                </a>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="rounded-xl border border-white/10 bg-white/[0.03] px-4 py-2 text-sm text-slate-200 transition hover:bg-white/[0.08]"
                    >
                        {{ __('store.logout') }}
                    </button>
                </form>

            @else

                <a
                    href="{{ route('login') }}"
                    class="hidden rounded-xl px-4 py-2 text-sm text-slate-300 transition hover:text-white sm:block"
                >
                    {{ __('store.login') }}
                </a>

                <a
                    href="{{ route('register') }}"
                    class="pc-button rounded-xl px-4 py-2 text-sm font-semibold text-white transition"
                >
                    {{ __('store.create_account') }}
                </a>

            @endauth

        </div>

    </div>

    <div class="border-t border-white/5 px-5 py-3 sm:hidden">

        <div class="mx-auto flex max-w-7xl items-center justify-between">

            <div class="flex gap-4 text-sm text-slate-400">

                <a href="{{ route('products.index') }}">
                    {{ __('store.products') }}
                </a>

                <a href="{{ route('orders.track') }}">
                    {{ __('store.track_order') }}
                </a>

                <a href="{{ route('help.index') }}">
                    {{ __('store.help') }}
                </a>

            </div>

            <div class="flex items-center gap-1">

                <a
                    href="{{ route('locale.update', 'az') }}"
                    class="rounded-md px-2 py-1 text-[11px]
                    {{ app()->getLocale() === 'az'
                        ? 'pc-brand-gradient text-white'
                        : 'text-slate-500' }}"
                >
                    AZ
                </a>

                <a
                    href="{{ route('locale.update', 'en') }}"
                    class="rounded-md px-2 py-1 text-[11px]
                    {{ app()->getLocale() === 'en'
                        ? 'pc-brand-gradient text-white'
                        : 'text-slate-500' }}"
                >
                    EN
                </a>

                <a
                    href="{{ route('locale.update', 'ru') }}"
                    class="rounded-md px-2 py-1 text-[11px]
                    {{ app()->getLocale() === 'ru'
                        ? 'pc-brand-gradient text-white'
                        : 'text-slate-500' }}"
                >
                    RU
                </a>

            </div>

        </div>

    </div>

</header>

<main class="min-h-[70vh]">
    @yield('content')
</main>

<footer class="border-t border-white/10 bg-[#070816]/92 backdrop-blur-xl">

    <div class="mx-auto grid max-w-7xl gap-10 px-5 py-14 md:grid-cols-4 lg:px-8">

        <div class="md:col-span-2">

            <div class="flex items-center gap-3">

                <div class="pc-brand-gradient pc-glow flex h-10 w-10 items-center justify-center rounded-xl font-black">
                    P
                </div>

                <div>
                    <div class="pc-brand-text font-black">
                        {{ config('company.brand_full_name') }}
                    </div>

                    <div class="text-xs text-slate-500">
                        Fast, secure digital gaming commerce
                    </div>
                </div>

            </div>

            <p class="mt-5 max-w-md text-sm leading-6 text-slate-400">
                Rəqəmsal oyun məhsulları və top-up xidmətləri.
                Şəffaf qiymətlər, qorunan sifariş prosesi və
                təhlükəsiz ödəniş yoxlaması.
            </p>

            <div class="mt-5 space-y-1 text-xs leading-5 text-slate-500">

                <div>
                    PlayCharge, {{ config('company.legal_short_name') }} tərəfindən idarə olunur.
                </div>

                <div>
                    VÖEN:
                    {{ config('company.tax_id') }}
                </div>

                <div>
                    {{ config('company.legal_address') }}
                </div>

                <div>
                    Tel:
                    {{ config('company.phone_display') }}
                </div>

                @if(config('company.support_email'))
                    <div>
                        Email:
                        {{ config('company.support_email') }}
                    </div>
                @endif

            </div>

        </div>

        <div>

            <h3 class="font-semibold">
                {{ __('store.customer') }}
            </h3>

            <div class="mt-4 flex flex-col gap-3 text-sm text-slate-400">

                <a
                    href="{{ route('products.index') }}"
                    class="hover:text-cyan-300"
                >
                    {{ __('store.products') }}
                </a>

                <a
                    href="{{ route('orders.track') }}"
                    class="hover:text-violet-300"
                >
                    {{ __('store.track_order') }}
                </a>

                <a
                    href="{{ route('help.index') }}"
                    class="hover:text-fuchsia-300"
                >
                    {{ __('store.help_center') }}
                </a>

                <a
                    href="{{ route('contact') }}"
                    class="hover:text-blue-300"
                >
                    {{ __('store.support') }}
                </a>

            </div>

        </div>

        <div>

            <h3 class="font-semibold">
                {{ __('store.legal') }}
            </h3>

            <div class="mt-4 flex flex-col gap-3 text-sm text-slate-400">

                <a class="hover:text-white" href="{{ route('legal.terms') }}">
                    {{ __('store.terms') }}
                </a>

                <a class="hover:text-white" href="{{ route('legal.privacy') }}">
                    {{ __('store.privacy') }}
                </a>

                <a class="hover:text-white" href="{{ route('legal.refund') }}">
                    {{ __('store.refund') }}
                </a>

                <a class="hover:text-white" href="{{ route('legal.delivery') }}">
                    {{ __('store.delivery_policy') }}
                </a>

                <a class="hover:text-white" href="{{ route('legal.security') }}">
                    {{ __('store.fraud_security') }}
                </a>

            </div>

        </div>

    </div>

    <div class="border-t border-white/10">

        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-5 py-6 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between lg:px-8">

            <span>
                © {{ date('Y') }}
                {{ config('company.brand_full_name') }}.
            </span>

            <span>
                {{ config('company.public_domain') }} • Secure digital commerce • AZN
            </span>

        </div>

    </div>

</footer>

</body>
</html>