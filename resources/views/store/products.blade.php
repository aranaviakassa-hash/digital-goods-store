@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8">

    <div class="max-w-2xl">
        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-cyan-400">
            Digital Catalogue
        </p>

        <h1 class="mt-3 text-4xl font-black">
            PlayCharge Products
        </h1>

        <p class="mt-4 leading-7 text-slate-400">
            Browse PlayCharge's digital gaming catalogue. Products shown in review preview are visible for information and bank-review purposes until commercial activation is complete.
        </p>
    </div>

    <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

        @forelse($products as $product)

            @php
                $isSellable = $product->isSellable();
                $name = strtolower($product->name);

                $style = str_contains($name, 'pubg')
                    ? [
                        'border' => 'hover:border-amber-400/40',
                        'badge' => 'bg-amber-400/10 text-amber-300 border-amber-400/20',
                        'button' => 'group-hover:bg-amber-500 group-hover:text-black',
                    ]
                    : (
                        str_contains($name, 'free')
                        ? [
                            'border' => 'hover:border-cyan-400/40',
                            'badge' => 'bg-cyan-400/10 text-cyan-300 border-cyan-400/20',
                            'button' => 'group-hover:bg-cyan-400 group-hover:text-black',
                        ]
                        : [
                            'border' => 'hover:border-pink-400/40',
                            'badge' => 'bg-pink-400/10 text-pink-300 border-pink-400/20',
                            'button' => 'group-hover:bg-pink-400 group-hover:text-black',
                        ]
                    );
            @endphp

            <a
                href="{{ route('products.show', $product) }}"
                class="group overflow-hidden rounded-[1.6rem] border border-white/[0.08] bg-[#0C101B] transition duration-300 hover:-translate-y-1 {{ $style['border'] }} hover:shadow-2xl hover:shadow-black/30"
            >
                <div class="h-44 overflow-hidden border-b border-white/[0.06]">
                    @include('store.partials.product-visual', ['product' => $product])
                </div>

                <div class="p-6">
                    <div class="flex items-center justify-between gap-4">
                        <span class="rounded-full border px-3 py-1 text-[10px] font-bold uppercase tracking-wider {{ $style['badge'] }}">
                            {{ $product->category }}
                        </span>

                        @if($isSellable)
                            <span class="flex items-center gap-1.5 text-[11px] font-semibold text-emerald-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                Available
                            </span>
                        @else
                            <span class="flex items-center gap-1.5 text-[11px] font-semibold text-amber-300">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-300"></span>
                                Review preview
                            </span>
                        @endif
                    </div>

                    <h2 class="mt-5 text-xl font-bold text-white">
                        {{ $product->name }}
                    </h2>

                    <div class="mt-6 flex items-end justify-between gap-4">
                        <div>
                            @if($product->price !== null)
                                <div class="text-[11px] uppercase tracking-wider text-slate-600">Price</div>
                                <strong class="mt-1 block text-2xl font-black">
                                    {{ number_format((float) $product->price, 2) }}
                                    <span class="text-sm font-semibold text-slate-500">{{ $product->currency }}</span>
                                </strong>
                            @else
                                <div class="text-[11px] uppercase tracking-wider text-slate-600">Pricing</div>
                                <div class="mt-1 text-sm font-semibold text-slate-300">Pending activation</div>
                            @endif
                        </div>

                        <span class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/10 bg-white/[0.04] text-lg transition {{ $style['button'] }}">
                            &rarr;
                        </span>
                    </div>
                </div>
            </a>

        @empty

            <div class="col-span-full rounded-[1.6rem] border border-dashed border-white/10 bg-white/[0.02] px-6 py-14 text-center">
                <div class="font-semibold text-white">Catalogue is being prepared</div>
                <p class="mt-2 text-sm text-slate-500">Products will appear here as catalogue information becomes available.</p>
            </div>

        @endforelse

    </div>

    <div class="mt-10 text-slate-300">
        {{ $products->links() }}
    </div>

</section>

@endsection
