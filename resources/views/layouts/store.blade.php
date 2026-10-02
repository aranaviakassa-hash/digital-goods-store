<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ $title ?? 'GameHub Digital' }}</title>

    <meta
        name="description"
        content="Secure digital gaming products and top-ups with transparent pricing and protected order processing."
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-white antialiased">

<header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/90 backdrop-blur-xl">

    <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">

        <a
            href="{{ route('home') }}"
            class="flex items-center gap-3"
        >
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-600 font-black shadow-lg shadow-violet-600/20">
                G
            </div>

            <div>
                <div class="text-lg font-bold tracking-tight">
                    GameHub
                </div>

                <div class="text-[10px] uppercase tracking-[0.22em] text-slate-500">
                    Digital Store
                </div>
            </div>
        </a>

        <nav class="hidden items-center gap-7 text-sm text-slate-300 md:flex">

            <a
                href="{{ route('products.index') }}"
                class="transition hover:text-white"
            >
                {{ __('store.products') }}
            </a>

            <a
                href="{{ route('orders.track') }}"
                class="transition hover:text-white"
            >
                {{ __('store.track_order') }}
            </a>

            <a
                href="{{ route('help.index') }}"
                class="transition hover:text-white"
            >
                {{ __('store.help') }}
            </a>

            <a
                href="{{ route('contact') }}"
                class="transition hover:text-white"
            >
                {{ __('store.support') }}
            </a>

        </nav>

        <div class="flex items-center gap-3">

            <div class="hidden items-center gap-1 rounded-xl border border-white/10 bg-white/[0.03] p-1 sm:flex">

                <a
                    href="{{ route('locale.update', 'az') }}"
                    class="rounded-lg px-2.5 py-1.5 text-xs font-semibold transition
                    {{ app()->getLocale() === 'az'
                        ? 'bg-violet-600 text-white'
                        : 'text-slate-400 hover:text-white' }}"
                >
                    AZ
                </a>

                <a
                    href="{{ route('locale.update', 'en') }}"
                    class="rounded-lg px-2.5 py-1.5 text-xs font-semibold transition
                    {{ app()->getLocale() === 'en'
                        ? 'bg-violet-600 text-white'
                        : 'text-slate-400 hover:text-white' }}"
                >
                    EN
                </a>

                <a
                    href="{{ route('locale.update', 'ru') }}"
                    class="rounded-lg px-2.5 py-1.5 text-xs font-semibold transition
                    {{ app()->getLocale() === 'ru'
                        ? 'bg-violet-600 text-white'
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
                        class="rounded-xl border border-white/10 px-4 py-2 text-sm text-slate-200 transition hover:bg-white/5"
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
                    class="rounded-xl bg-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-violet-600/20 transition hover:bg-violet-500"
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
                        ? 'bg-violet-600 text-white'
                        : 'text-slate-500' }}"
                >
                    AZ
                </a>

                <a
                    href="{{ route('locale.update', 'en') }}"
                    class="rounded-md px-2 py-1 text-[11px]
                    {{ app()->getLocale() === 'en'
                        ? 'bg-violet-600 text-white'
                        : 'text-slate-500' }}"
                >
                    EN
                </a>

                <a
                    href="{{ route('locale.update', 'ru') }}"
                    class="rounded-md px-2 py-1 text-[11px]
                    {{ app()->getLocale() === 'ru'
                        ? 'bg-violet-600 text-white'
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

<footer class="border-t border-white/10 bg-slate-950">

    <div class="mx-auto grid max-w-7xl gap-10 px-5 py-14 md:grid-cols-4 lg:px-8">

        <div class="md:col-span-2">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-600 font-black">
                    G
                </div>

                <div>
                    <div class="font-bold">
                        GameHub Digital Store
                    </div>

                    <div class="text-xs text-slate-500">
                        Secure digital gaming commerce
                    </div>
                </div>

            </div>

            <p class="mt-5 max-w-md text-sm leading-6 text-slate-400">
                Digital gaming products and top-ups with transparent
                pricing, secure payment verification and protected
                order processing.
            </p>

        </div>

        <div>

            <h3 class="font-semibold">
                {{ __('store.customer') }}
            </h3>

            <div class="mt-4 flex flex-col gap-3 text-sm text-slate-400">

                <a
                    href="{{ route('products.index') }}"
                    class="hover:text-white"
                >
                    {{ __('store.products') }}
                </a>

                <a
                    href="{{ route('orders.track') }}"
                    class="hover:text-white"
                >
                    {{ __('store.track_order') }}
                </a>

                <a
                    href="{{ route('help.index') }}"
                    class="hover:text-white"
                >
                    {{ __('store.help_center') }}
                </a>

                <a
                    href="{{ route('contact') }}"
                    class="hover:text-white"
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

                <a href="{{ route('legal.terms') }}">
                    {{ __('store.terms') }}
                </a>

                <a href="{{ route('legal.privacy') }}">
                    {{ __('store.privacy') }}
                </a>

                <a href="{{ route('legal.refund') }}">
                    {{ __('store.refund') }}
                </a>

                <a href="{{ route('legal.delivery') }}">
                    {{ __('store.delivery_policy') }}
                </a>

                <a href="{{ route('legal.security') }}">
                    {{ __('store.fraud_security') }}
                </a>

            </div>

        </div>

    </div>

    <div class="border-t border-white/10">

        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-5 py-6 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between lg:px-8">

            <span>
                © {{ date('Y') }} GameHub Digital Store.
            </span>

            <span>
                Secure digital commerce • AZN
            </span>

        </div>

    </div>

</footer>

</body>
</html>