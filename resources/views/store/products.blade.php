@extends('layouts.store')

@section('content')
<section class="relative overflow-hidden border-b border-white/[0.07] bg-[#060911]">
    <div class="absolute left-[-8rem] top-0 h-72 w-72 rounded-full bg-blue-500/10 blur-[110px]"></div>
    <div class="absolute right-[-6rem] top-8 h-72 w-72 rounded-full bg-emerald-500/8 blur-[110px]"></div>

    <div class="mx-auto grid max-w-7xl gap-8 px-5 py-16 lg:grid-cols-[1fr_390px] lg:items-end lg:px-8 lg:py-20">
        <div class="max-w-3xl">
            <p class="pc-kicker text-blue-400">{{ __('store.catalogue_eyebrow') }}</p>
            <h1 class="mt-4 text-4xl font-black tracking-[-0.04em] text-white sm:text-5xl lg:text-6xl">{{ __('store.catalogue_title') }}</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-400">{{ __('store.catalogue_intro') }}</p>
        </div>

        <div class="pc-panel rounded-[1.6rem] p-5">
            <div class="flex items-center gap-2 text-xs font-black uppercase tracking-[0.16em] text-blue-200">
                <span class="pc-status-dot h-2 w-2 rounded-full bg-blue-400 text-blue-400"></span>{{ __('store.review_preview') }}
            </div>
            <p class="mt-3 text-sm leading-6 text-slate-500">{{ __('store.bank_review_preview_text') }}</p>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
    <div class="mb-9 flex flex-col gap-5 border-b border-white/[0.07] pb-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="pc-kicker text-slate-600">{{ __('store.featured') }}</div>
            <div class="mt-2 text-2xl font-black tracking-[-0.02em] text-white">{{ __('store.popular_products') }}</div>
        </div>
        <div class="inline-flex min-h-10 items-center rounded-full border border-white/[0.08] bg-white/[0.025] px-4 text-xs font-bold text-slate-500">{{ $products->total() }} {{ strtolower(__('store.products')) }}</div>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($products as $product)
            @include('store.partials.home-product-card', ['product' => $product])
        @empty
            <div class="pc-panel col-span-full rounded-[1.8rem] border-dashed px-6 py-16 text-center">
                <div class="font-bold text-white">{{ __('store.catalog_preparing') }}</div>
                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">{{ __('store.catalog_preparing_text') }}</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12 text-slate-300">{{ $products->links() }}</div>
</section>
@endsection
