@extends('layouts.store')

@section('content')

<section class="mx-auto flex min-h-[65vh] max-w-7xl items-center justify-center px-5 py-16">

    <div class="w-full max-w-md">

        <div class="text-center">

            <h1 class="text-3xl font-black">
                Reset your password
            </h1>

            <p class="mt-3 text-sm text-slate-400">
                Enter your email and we'll send you a reset link.
            </p>

        </div>

        <form
            method="POST"
            action="{{ route('password.email') }}"
            class="mt-8 space-y-5 rounded-2xl border border-white/10 bg-slate-900 p-6"
        >

            @csrf

            <div>

                <label class="text-sm text-slate-300">
                    Email address
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 outline-none focus:border-violet-500"
                >

            </div>

            @if(session('status'))

                <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-emerald-300">
                    {{ session('status') }}
                </div>

            @endif

            @if($errors->any())

                <div class="rounded-xl border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-300">
                    {{ $errors->first() }}
                </div>

            @endif

            <button
                class="w-full rounded-xl bg-violet-600 py-3.5 font-semibold hover:bg-violet-500"
            >
                Send reset link
            </button>

        </form>

    </div>

</section>

@endsection