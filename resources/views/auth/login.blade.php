@extends('layouts.store')

@section('content')

<div class="mx-auto max-w-md">
    <h1 class="text-3xl font-bold">Login</h1>

    <form
        method="POST"
        action="{{ route('login.submit') }}"
        class="mt-8 space-y-5 rounded-xl border bg-white p-6"
    >
        @csrf

        <div>
            <label class="text-sm font-medium">Email</label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                class="mt-2 w-full rounded-lg border px-4 py-3"
            >
        </div>

        <div>
            <label class="text-sm font-medium">Password</label>

            <input
                type="password"
                name="password"
                required
                class="mt-2 w-full rounded-lg border px-4 py-3"
            >
        </div>

        @if($errors->any())
            <div class="text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <button
            type="submit"
            class="w-full rounded-lg bg-black py-3 text-white"
        >
            Login
        </button>
    </form>
</div>

@endsection