@extends('layouts.store')

@section('content')

<div class="mx-auto max-w-xl">

    <h1 class="text-3xl font-bold">
        Track Order
    </h1>

    <form
        method="POST"
        action="{{ route('orders.track.submit') }}"
        class="mt-8 space-y-5 rounded-xl border bg-white p-6"
    >
        @csrf

        <div>
            <label class="block text-sm font-medium">
                Order Number
            </label>

            <input
                type="text"
                name="order_number"
                required
                class="mt-2 w-full rounded-lg border px-4 py-3"
            >
        </div>

        <div>
            <label class="block text-sm font-medium">
                Email
            </label>

            <input
                type="email"
                name="customer_email"
                required
                class="mt-2 w-full rounded-lg border px-4 py-3"
            >
        </div>

        @if($errors->any())
            <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <button class="w-full rounded-lg bg-black px-6 py-3 text-white">
            Track Order
        </button>
    </form>

</div>

@endsection