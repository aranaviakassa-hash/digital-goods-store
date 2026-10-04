@php
    $name = strtolower($product->name);
    $isPurchasable = $product->isPurchasableNow();
    $categoryLabel = strcasecmp((string) $product->category, 'Direct Top-Up') === 0
        ? __('store.direct_topup')
        : $product->category;

    $style = str_contains($name, 'pubg')
        ? ['line' => 'from-amber-400 via-orange-400 to-transparent', 'badge' => 'border-amber-400/20 bg-amber-400/[0.08] text-amber-300']
        : (str_contains($name, 'free')
            ? ['line' => 'from-cyan-400 via-blue-400 to-transparent', 'badge' => 'border-cyan-400/20 bg-cyan-400/[0.08] text-cyan-300']
            : ['line' => 'from-violet-400 via-fuchsia-400 to-transparent', 'badge' => 'border-violet-400/20 bg-violet-400/[0.08] text-violet-300']);
@endphp

<a href="{{ route('products.show', $product) }}" class="pc-card group overflow-hidden rounded-[1.8rem] transition duration-300 hover:-translate-y-1.5 hover:border-blue-400/25 hover:shadow-[0_24px_70px_rgba(0,0,0,.36)]">
    <div class="h-1 w-full bg-gradient-to-r {{ $style['line'] }} opacity-80"></div>

    <div class="relative h-52 overflow-hidden border-b border-white/[0.06]">
        @include('store.partials.product-visual', ['product' => $product])
        <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-[#070b13] to-transparent"></div>

        <div class="absolute left-4 top-4 rounded-full border px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.13em] backdrop-blur-md {{ $style['badge'] }}">
            {{ $categoryLabel }}
        </div>
    </div>

    <div class="relative p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-[1.35rem] font-black leading-tight tracking-[-0.02em] text-white">{{ $product->name }}</h3>
                <div class="mt-2 inline-flex items-center gap-2 text-[11px] font-bold {{ $isPurchasable ? 'text-emerald-300' : 'text-blue-300' }}">
                    <span class="pc-status-dot h-1.5 w-1.5 rounded-full {{ $isPurchasable ? 'bg-emerald-400 text-emerald-400' : 'bg-blue-400 text-blue-400' }}"></span>
                    {{ $isPurchasable ? __('store.available') : __('store.review_preview') }}
                </div>
            </div>

            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/[0.08] bg-white/[0.03] text-slate-500 transition group-hover:border-blue-400/30 group-hover:bg-blue-400/[0.08] group-hover:text-blue-200">↗</span>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-3">
            <div class="rounded-xl border border-white/[0.06] bg-black/20 px-4 py-3">
                <div class="pc-kicker text-[9px] text-slate-700">{{ __('store.pricing') }}</div>
                @if($product->price !== null && (float) $product->price > 0)
                    <div class="mt-1 text-lg font-black text-white">{{ number_format((float) $product->price, 2) }} <span class="text-xs text-slate-500">{{ $product->currency }}</span></div>
                @else
                    <div class="mt-1 text-xs font-bold text-slate-300">{{ __('store.pending_activation') }}</div>
                @endif
            </div>

            <div class="rounded-xl border border-white/[0.06] bg-black/20 px-4 py-3">
                <div class="pc-kicker text-[9px] text-slate-700">{{ __('store.delivery') }}</div>
                <div class="mt-1 text-xs font-bold text-slate-300">{{ __('store.digital_fulfillment') }}</div>
            </div>
        </div>
    </div>
</a>
