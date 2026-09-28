<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ $title ?? 'Digital Goods Store' }}</title>

    <meta
        name="description"
        content="Secure digital gaming products and top-ups."
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-900">

<header class="border-b bg-white">
    <div
        class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5"
    >

        <a
            href="{{ route('home') }}"
            class="text-xl font-bold"
        >
            Digital Goods Store
        </a>

        <nav class="flex items-center gap-6 text-sm">

            <a
                href="{{ route('products.index') }}"
                class="hover:underline"
            >
                Products
            </a>

            <a
                href="{{ route('orders.track') }}"
                class="hover:underline"
            >
                Track Order
            </a>

            <a
                href="{{ route('contact') }}"
                class="hover:underline"
            >
                Contact
            </a>

            @auth

                <span class="hidden text-gray-500 md:inline">
                    {{ auth()->user()->name }}
                </span>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="rounded-lg border px-4 py-2"
                    >
                        Logout
                    </button>
                </form>

            @else

                <a
                    href="{{ route('login') }}"
                    class="hover:underline"
                >
                    Login
                </a>

                <a
                    href="{{ route('register') }}"
                    class="rounded-lg bg-black px-4 py-2 text-white"
                >
                    Register
                </a>

            @endauth

        </nav>

    </div>
</header>

<main class="mx-auto min-h-[65vh] max-w-6xl px-6 py-10">

    @yield('content')

</main>

<footer class="border-t bg-white">

    <div
        class="mx-auto grid max-w-6xl gap-8 px-6 py-10 md:grid-cols-3"
    >

        <div>
            <h3 class="font-semibold">
                Digital Goods Store
            </h3>

            <p class="mt-3 text-sm text-gray-500">
                Secure digital gaming products and top-ups.
            </p>
        </div>

        <div>
            <h3 class="font-semibold">
                Policies
            </h3>

            <div class="mt-3 flex flex-col gap-2 text-sm text-gray-500">

                <a href="{{ route('legal.terms') }}">
                    Terms & Conditions
                </a>

                <a href="{{ route('legal.privacy') }}">
                    Privacy Policy
                </a>

                <a href="{{ route('legal.refund') }}">
                    Refund Policy
                </a>

                <a href="{{ route('legal.delivery') }}">
                    Delivery Policy
                </a>

                <a href="{{ route('legal.security') }}">
                    Fraud & Security
                </a>

            </div>
        </div>

        <div>
            <h3 class="font-semibold">
                Customer Support
            </h3>

            <p class="mt-3 text-sm text-gray-500">
                For order and payment questions, use our contact page.
            </p>

            <a
                href="{{ route('contact') }}"
                class="mt-3 inline-block text-sm underline"
            >
                Contact us
            </a>
        </div>

    </div>

    <div class="border-t">

        <div
            class="mx-auto max-w-6xl px-6 py-5 text-xs text-gray-500"
        >
            © {{ date('Y') }} Digital Goods Store.
            All rights reserved.
        </div>

    </div>

</footer>

</body>
</html>