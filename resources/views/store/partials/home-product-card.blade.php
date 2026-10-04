@php
    $name = strtolower($product->name);
    $isSellable = $product->isSellable();

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
    class="group relative overflow-hidden rounded-[1.6rem] border border-white/[0.08] bg-[#0C101B] transition duration-300 hover:-translate-y-1 {{ $style['border'] }} hover:shadow-2xl hover:shadow-black/30"
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
                    {{ __('store.available') }}
                </span>
            @else
                <span class="flex items-center gap-1.5 text-[11px] font-semibold text-amber-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-300"></span>
                    {{ __('store.review_preview') }}
                </span>
            @endif
        </div>

        <h3 class="mt-5 text-xl font-bold text-white">{{ $product->name }}</h3>

        <div class="mt-6 flex items-end justify-between gap-4">
            <div>
                @if($product->price !== null)
                    <div class="text-[11px] uppercase tracking-wider text-slate-600">{{ __('store.from') }}</div>
                    <div class="mt-1 text-2xl font-black">
                        {{ number_format((float) $product->price, 2) }}
                        <span class="text-sm font-semibold text-slate-500">{{ $product->currency }}</span>
                    </div>
                @else
                    <div class="text-[11px] uppercase tracking-wider text-slate-600">{{ __('store.pricing') }}</div>
                    <div class="mt-1 max-w-44 text-sm font-semibold leading-5 text-slate-300">{{ __('store.pending_activation') }}</div>
                @endif
            </div>

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/[0.04] text-lg transition duration-300 {{ $style['button'] }}">&rarr;</div>
        </div>
    </div>
</a>
