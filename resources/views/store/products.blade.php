@extends('layouts.store')

@section('content')
<section class="relative overflow-hidden border-b border-white/[0.07] bg-[#080a16]">
    <div class="absolute inset-0 -z-10 opacity-80" style="background:radial-gradient(circle at 15% 30%,rgba(34,211,238,.10),transparent 24rem),radial-gradient(circle at 82% 22%,rgba(139,92,246,.14),transparent 28rem);"></div>
    <div class="mx-auto grid max-w-7xl gap-10 px-5 py-16 lg:grid-cols-[1fr_380px] lg:items-end lg:px-8 lg:py-20">
        <div class="max-w-3xl">
            <p class="text-xs font-black uppercase tracking-[0.24em] text-cyan-400">{{ __('store.catalogue_eyebrow') }}</p>
            <h1 class="mt-4 text-4xl font-black tracking-[-0.03em] text-white sm:text-5xl">{{ __('store.catalogue_title') }}</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-400">{{ __('store.catalogue_intro') }}</p>
        </div>

        <div class="rounded-[1.6rem] border border-amber-300/15 bg-amber-300/[0.045] p-5">
            <div class="flex items-center gap-2 text-xs font-black uppercase tracking-[0.16em] text-amber-200">
                <span class="h-2 w-2 rounded-full bg-amber-300"></span>{{ __('store.review_preview') }}
            </div>
            <p class="mt-3 text-sm leading-6 text-slate-400">{{ __('store.bank_review_preview_text') }}</p>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-5 py-14 lg:px-8 lg:py-16">
    <div class="mb-8 flex flex-col gap-4 border-b border-white/[0.06] pb-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-600">{{ __('store.featured') }}</div>
            <div class="mt-2 text-xl font-black text-white">{{ __('store.popular_products') }}</div>
        </div>
        <div class="text-sm text-slate-500">{{ $products->total() }} {{ strtolower(__('store.products')) }}</div>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($products as $product)
            @include('store.partials.home-product-card', ['product' => $product])
        @empty
            <div class="col-span-full rounded-[1.8rem] border border-dashed border-white/10 bg-white/[0.02] px-6 py-16 text-center">
                <div class="font-semibold text-white">{{ __('store.catalog_preparing') }}</div>
                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">{{ __('store.catalog_preparing_text') }}</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12 text-slate-300">{{ $products->links() }}</div>
</section>
@endsection
