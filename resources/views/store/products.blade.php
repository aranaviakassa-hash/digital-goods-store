@extends('layouts.store')

@section('content')

<h1 class="text-3xl font-bold">
    Products
</h1>

<div class="mt-8 grid gap-6 md:grid-cols-3">
    @forelse($products as $product)

        <div class="rounded-xl border bg-white p-6">
            <h2 class="font-semibold">
                {{ $product->name }}
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                {{ $product->category }}
            </p>

            <p class="mt-5 text-xl font-bold">
                {{ number_format($product->price, 2) }}
                {{ $product->currency }}
            </p>

            <a
                href="{{ route('products.show', $product) }}"
                class="mt-5 inline-block rounded-lg bg-black px-4 py-2 text-white"
            >
                View
            </a>
        </div>

    @empty

        <p>No products available.</p>

    @endforelse
</div>

<div class="mt-10">
    {{ $products->links() }}
</div>

@endsection