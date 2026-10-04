@php
    $name = strtolower($product->name);
    $isSellable = $product->isSellable();
    $categoryLabel = strcasecmp((string) $product->category, 'Direct Top-Up') === 0
        ? __('store.direct_topup')
        : $product->category;

    $style = str_contains($name, 'pubg')
        ? ['accent' => 'from-amber-400/25 via-transparent to-transparent', 'badge' => 'border-amber-400/20 bg-amber-400/10 text-amber-300']
        : (str_contains($name, 'free')
            ? ['accent' => 'from-cyan-400/20 via-transparent to-transparent', 'badge' => 'border-cyan-400/20 bg-cyan-400/10 text-cyan-300']
            : ['accent' => 'from-pink-400/20 via-transparent to-transparent', 'badge' => 'border-pink-400/20 bg-pink-400/10 text-pink-300']);
@endphp

<a href="{{ route('products.show', $product) }}" class="group relative overflow-hidden rounded-[1.7rem] border border-white/[0.08] bg-[#0b0e1b] transition duration-300 hover:-translate-y-1.5 hover:border-white/[0.16] hover:shadow-[0_24px_70px_rgba(0,0,0,.34)]">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-40 bg-gradient-to-b {{ $style['accent'] }} opacity-70"></div>

    <div class="relative h-48 overflow-hidden border-b border-white/[0.06]">
        @include('store.partials.product-visual', ['product' => $product])
        <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-[#0b0e1b] to-transparent"></div>
    </div>

    <div class="relative p-6">
        <div class="flex items-center justify-between gap-3">
            <span class="rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-[0.14em] {{ $style['badge'] }}">{{ $categoryLabel }}</span>
            @if($isSellable)
                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-emerald-400"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>{{ __('store.available') }}</span>
            @else
                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-amber-300"><span class="h-1.5 w-1.5 rounded-full bg-amber-300"></span>{{ __('store.review_preview') }}</span>
            @endif
        </div>

        <h3 class="mt-5 text-[1.35rem] font-black leading-tight text-white">{{ $product->name }}</h3>

        <div class="mt-6 flex items-end justify-between gap-4 border-t border-white/[0.06] pt-5">
            <div>
                @if($product->price !== null)
                    <div class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-600">{{ __('store.from') }}</div>
                    <div class="mt-1 text-2xl font-black text-white">{{ number_format((float) $product->price, 2) }} <span class="text-sm font-semibold text-slate-500">{{ $product->currency }}</span></div>
                @else
                    <div class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-600">{{ __('store.pricing') }}</div>
                    <div class="mt-1 text-sm font-semibold text-slate-300">{{ __('store.pending_activation') }}</div>
                @endif
            </div>

            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/[0.04] text-lg text-slate-400 transition duration-300 group-hover:border-violet-400/30 group-hover:bg-violet-500/10 group-hover:text-white">→</span>
        </div>
    </div>
</a>
