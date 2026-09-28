@extends('layouts.store')

@section('content')

<section class="py-14 text-center">
    <h1 class="text-4xl font-bold">
        Fast & Secure Digital Gaming Products
    </h1>

    <p class="mx-auto mt-4 max-w-2xl text-gray-600">
        Buy approved gaming top-ups and digital products with transparent pricing
        and secure order processing.
    </p>

    <a
        href="{{ route('products.index') }}"
        class="mt-8 inline-block rounded-lg bg-black px-6 py-3 text-white"
    >
        Browse Products
    </a>
</section>

<section class="mt-10">
    <h2 class="mb-6 text-2xl font-semibold">
        Available Products
    </h2>

    <div class="grid gap-6 md:grid-cols-3">
        @forelse($products as $product)

            <div class="rounded-xl border bg-white p-6">
                <h3 class="font-semibold">
                    {{ $product->name }}
                </h3>

                <p class="mt-2 text-sm text-gray-500">
                    {{ $product->category }}
                </p>

                <p class="mt-5 text-xl font-bold">
                    {{ number_format($product->price, 2) }}
                    {{ $product->currency }}
                </p>

                <a
                    href="{{ route('products.show', $product) }}"
                    class="mt-5 inline-block text-sm font-semibold underline"
                >
                    View product
                </a>
            </div>

        @empty

            <p>No products are currently available.</p>

        @endforelse
    </div>
</section>

@endsection