@extends('layouts.store')

@section('content')

<section class="mx-auto flex min-h-[65vh] max-w-7xl items-center justify-center px-5 py-16">

    <div class="w-full max-w-md">

        <h1 class="text-center text-3xl font-black">
            Choose a new password
        </h1>

        <form
            method="POST"
            action="{{ route('password.update') }}"
            class="mt-8 space-y-5 rounded-2xl border border-white/10 bg-slate-900 p-6"
        >

            @csrf

            <input
                type="hidden"
                name="token"
                value="{{ $token }}"
            >

            <div>

                <label class="text-sm text-slate-300">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $email) }}"
                    required
                    class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3"
                >

            </div>

            <div>

                <label class="text-sm text-slate-300">
                    New password
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3"
                >

            </div>

            <div>

                <label class="text-sm text-slate-300">
                    Confirm password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3"
                >

            </div>

            @if($errors->any())

                <div class="rounded-xl bg-red-500/10 p-4 text-sm text-red-300">
                    {{ $errors->first() }}
                </div>

            @endif

            <button
                class="w-full rounded-xl bg-violet-600 py-3.5 font-semibold hover:bg-violet-500"
            >
                Reset password
            </button>

        </form>

    </div>

</section>

@endsection