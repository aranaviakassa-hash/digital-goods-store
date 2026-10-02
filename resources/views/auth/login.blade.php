@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-xl px-5 py-20 lg:px-8">

    <div class="rounded-3xl border border-white/10 bg-slate-900/80 p-8 shadow-2xl shadow-black/20">

        <div class="text-center">

            <h1 class="text-3xl font-bold">
                Sign in
            </h1>

            <p class="mt-3 text-sm text-slate-400">
                Access your account and manage your orders.
            </p>

        </div>

        @if (session('status'))
            <div class="mt-6 rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-emerald-300">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 rounded-xl border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-300">

                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach

            </div>
        @endif

        <a
            href="{{ route('auth.google.redirect') }}"
            class="mt-8 flex w-full items-center justify-center gap-3 rounded-xl border border-white/10 bg-white px-5 py-3 font-semibold text-slate-900 transition hover:bg-slate-100"
        >
            <svg
                viewBox="0 0 24 24"
                class="h-5 w-5"
                aria-hidden="true"
            >
                <path
                    fill="#4285F4"
                    d="M21.35 12.24c0-.72-.06-1.41-.18-2.08H12v3.94h5.24a4.48 4.48 0 0 1-1.94 2.94v2.44h3.14c1.84-1.69 2.91-4.19 2.91-7.24z"
                />
                <path
                    fill="#34A853"
                    d="M12 21.75c2.63 0 4.84-.87 6.45-2.36l-3.14-2.44c-.87.58-1.98.93-3.31.93-2.54 0-4.69-1.72-5.46-4.03H3.3v2.53A9.75 9.75 0 0 0 12 21.75z"
                />
                <path
                    fill="#FBBC05"
                    d="M6.54 13.85A5.86 5.86 0 0 1 6.23 12c0-.64.11-1.26.31-1.85V7.62H3.3A9.75 9.75 0 0 0 2.25 12c0 1.57.38 3.05 1.05 4.38l3.24-2.53z"
                />
                <path
                    fill="#EA4335"
                    d="M12 6.12c1.43 0 2.71.49 3.72 1.45l2.79-2.79C16.83 3.21 14.63 2.25 12 2.25A9.75 9.75 0 0 0 3.3 7.62l3.24 2.53C7.31 7.84 9.46 6.12 12 6.12z"
                />
            </svg>

            Continue with Google
        </a>

        <div class="my-7 flex items-center gap-4">

            <div class="h-px flex-1 bg-white/10"></div>

            <span class="text-xs uppercase tracking-wider text-slate-500">
                or
            </span>

            <div class="h-px flex-1 bg-white/10"></div>

        </div>

        <form
            method="POST"
            action="{{ route('login.submit') }}"
            class="space-y-5"
        >
            @csrf

            <div>

                <label
                    for="email"
                    class="mb-2 block text-sm font-medium text-slate-300"
                >
                    Email address
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                    class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none transition focus:border-violet-500"
                >

            </div>

            <div>

                <div class="mb-2 flex items-center justify-between">

                    <label
                        for="password"
                        class="text-sm font-medium text-slate-300"
                    >
                        Password
                    </label>

                    <a
                        href="{{ route('password.request') }}"
                        class="text-xs font-medium text-violet-400 hover:text-violet-300"
                    >
                        Forgot password?
                    </a>

                </div>

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none transition focus:border-violet-500"
                >

            </div>

            <label class="flex items-center gap-3 text-sm text-slate-400">

                <input
                    type="checkbox"
                    name="remember"
                    value="1"
                    class="h-4 w-4 rounded border-white/20 bg-slate-950 text-violet-600 focus:ring-violet-500"
                >

                Remember me

            </label>

            <button
                type="submit"
                class="w-full rounded-xl bg-violet-600 px-5 py-3 font-semibold text-white transition hover:bg-violet-500"
            >
                Sign in
            </button>

        </form>

        <p class="mt-7 text-center text-sm text-slate-400">

            Don't have an account?

            <a
                href="{{ route('register') }}"
                class="font-semibold text-violet-400 hover:text-violet-300"
            >
                Create account
            </a>

        </p>

    </div>

</section>

@endsection