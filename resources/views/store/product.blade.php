@extends('layouts.store')

@section('content')

<div class="mx-auto max-w-3xl rounded-xl border bg-white p-8">
    <p class="text-sm text-gray-500">
        {{ $product->category }}
    </p>

    <h1 class="mt-2 text-3xl font-bold">
        {{ $product->name }}
    </h1>

    @if($product->description)
        <p class="mt-5 text-gray-600">
            {{ $product->description }}
        </p>
    @endif

    <p class="mt-8 text-3xl font-bold">
        {{ number_format($product->price, 2) }}
        {{ $product->currency }}
    </p>

    <div class="mt-8 rounded-lg bg-gray-50 p-4 text-sm text-gray-600">
        Digital products are processed after successful payment and security verification.
    </div>

    <a
        href="{{ route('checkout.show', $product) }}"
        class="mt-8 inline-block rounded-lg bg-black px-6 py-3 text-white"
    >
        Continue to Checkout
    </a>
</div>

@endsection