@extends('layouts.store')

@section('content')

<section class="relative overflow-hidden">
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-72 bg-[radial-gradient(circle_at_20%_0%,rgba(34,211,238,.10),transparent_34%),radial-gradient(circle_at_80%_0%,rgba(124,58,237,.14),transparent_36%)]"></div>

    <div class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-400">
                    {{ __('store.catalogue_eyebrow') }}
                </p>

                <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">
                    {{ __('store.catalogue_title') }}
                </h1>

                <p class="mt-5 max-w-xl text-sm leading-7 text-slate-400 sm:text-base">
                    {{ __('store.catalogue_intro') }}
                </p>
            </div>

            <div class="max-w-sm rounded-2xl border border-white/[0.08] bg-white/[0.025] px-5 py-4 text-sm leading-6 text-slate-400 backdrop-blur">
                <div class="flex items-center gap-2 font-semibold text-white">
                    <span class="h-2 w-2 rounded-full bg-amber-300"></span>
                    {{ __('store.review_preview') }}
                </div>
                <div class="mt-1">{{ __('store.bank_review_preview_text') }}</div>
            </div>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($products as $product)
                @include('store.partials.home-product-card', ['product' => $product])
            @empty
                <div class="col-span-full rounded-[1.8rem] border border-dashed border-white/10 bg-white/[0.02] px-6 py-16 text-center">
                    <div class="font-semibold text-white">{{ __('store.catalog_preparing') }}</div>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">{{ __('store.catalog_preparing_text') }}</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12 text-slate-300">
            {{ $products->links() }}
        </div>
    </div>
</section>

@endsection
