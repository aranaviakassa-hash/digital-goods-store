@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8">
    <div class="max-w-2xl">
        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-cyan-400">
            {{ __('store.catalogue_eyebrow') }}
        </p>

        <h1 class="mt-3 text-4xl font-black">
            {{ __('store.catalogue_title') }}
        </h1>

        <p class="mt-4 leading-7 text-slate-400">
            {{ __('store.catalogue_intro') }}
        </p>
    </div>

    <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($products as $product)
            @include('store.partials.home-product-card', ['product' => $product])
        @empty
            <div class="col-span-full rounded-[1.6rem] border border-dashed border-white/10 bg-white/[0.02] px-6 py-14 text-center">
                <div class="font-semibold text-white">{{ __('store.catalog_preparing') }}</div>
                <p class="mt-2 text-sm text-slate-500">{{ __('store.catalog_preparing_text') }}</p>
            </div>
        @endforelse
    </div>

    <div class="mt-10 text-slate-300">
        {{ $products->links() }}
    </div>
</section>

@endsection
