@extends('layouts.store')

@section('content')

<section class="mx-auto flex min-h-[65vh] max-w-7xl items-center justify-center px-5 py-16">

    <div class="w-full max-w-lg rounded-2xl border border-white/10 bg-slate-900 p-8 text-center">

        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-violet-500/10 text-2xl">
            ✉️
        </div>

        <h1 class="mt-6 text-3xl font-black">
            Verify your email
        </h1>

        <p class="mt-4 leading-7 text-slate-400">
            We've sent a verification link to
            <strong class="text-white">
                {{ auth()->user()->email }}
            </strong>.
            Open the email and confirm your address to access your account.
        </p>

        @if(session('status'))

            <div class="mt-5 rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-emerald-300">
                {{ session('status') }}
            </div>

        @endif

        <form
            method="POST"
            action="{{ route('verification.send') }}"
            class="mt-7"
        >

            @csrf

            <button
                class="rounded-xl bg-violet-600 px-6 py-3 font-semibold hover:bg-violet-500"
            >
                Resend verification email
            </button>

        </form>

    </div>

</section>

@endsection