@extends('layouts.store')

@section('content')

<div class="mx-auto max-w-md">
    <h1 class="text-3xl font-bold">Create Account</h1>

    <form
        method="POST"
        action="{{ route('register.submit') }}"
        class="mt-8 space-y-5 rounded-xl border bg-white p-6"
    >
        @csrf

        <input
            type="text"
            name="name"
            value="{{ old('name') }}"
            placeholder="Name"
            required
            class="w-full rounded-lg border px-4 py-3"
        >

        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="Email"
            required
            class="w-full rounded-lg border px-4 py-3"
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
            class="w-full rounded-lg border px-4 py-3"
        >

        <input
            type="password"
            name="password_confirmation"
            placeholder="Confirm password"
            required
            class="w-full rounded-lg border px-4 py-3"
        >

        @if($errors->any())
            <div class="text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <button
            type="submit"
            class="w-full rounded-lg bg-black py-3 text-white"
        >
            Register
        </button>
    </form>
</div>

@endsection