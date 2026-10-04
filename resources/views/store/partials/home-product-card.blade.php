@php
    $name = strtolower($product->name);
    $isSellable = $product->isSellable();
    $categoryLabel = strcasecmp((string) $product->category, 'Direct Top-Up') === 0
        ? __('store.direct_topup')
        : $product->category;

    $style = str_contains($name, 'pubg')
        ? [
            'border' => 'hover:border-amber-400/45',
            'badge' => 'bg-amber-400/10 text-amber-200 border-amber-400/20',
            'glow' => 'from-amber-400/14 via-transparent to-transparent',
            'button' => 'group-hover:border-amber-300/40 group-hover:bg-amber-400/10 group-hover:text-amber-200',
        ]
        : (
            str_contains($name, 'free')
            ? [
                'border' => 'hover:border-cyan-400/45',
                'badge' => 'bg-cyan-400/10 text-cyan-200 border-cyan-400/20',
                'glow' => 'from-cyan-400/14 via-transparent to-transparent',
                'button' => 'group-hover:border-cyan-300/40 group-hover:bg-cyan-400/10 group-hover:text-cyan-200',
            ]
            : [
                'border' => 'hover:border-pink-400/45',
                'badge' => 'bg-pink-400/10 text-pink-200 border-pink-400/20',
                'glow' => 'from-pink-400/14 via-transparent to-transparent',
                'button' => 'group-hover:border-pink-300/40 group-hover:bg-pink-400/10 group-hover:text-pink-200',
            ]
        );
@endphp

<a
    href="{{ route('products.show', $product) }}"
    class="group relative flex min-h-[360px] flex-col overflow-hidden rounded-[1.75rem] border border-white/[0.08] bg-[#0B0E19] transition duration-300 hover:-translate-y-1 {{ $style['border'] }} hover:shadow-2xl hover:shadow-black/40"
>
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-br {{ $style['glow'] }} opacity-0 transition duration-300 group-hover:opacity-100"></div>

    <div class="relative h-48 overflow-hidden border-b border-white/[0.06] bg-black/10">
        <div class="absolute inset-x-0 bottom-0 z-10 h-16 bg-gradient-to-t from-[#0B0E19] to-transparent"></div>
        @include('store.partials.product-visual', ['product' => $product])
    </div>

    <div class="relative flex flex-1 flex-col p-6">
        <div class="flex items-start justify-between gap-4">
            <span class="rounded-full border px-3 py-1 text-[10px] font-bold uppercase tracking-[0.12em] {{ $style['badge'] }}">
                {{ $categoryLabel }}
            </span>

            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[10px] font-semibold {{ $isSellable ? 'border-emerald-400/15 bg-emerald-400/[0.06] text-emerald-300' : 'border-amber-400/15 bg-amber-400/[0.06] text-amber-200' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ $isSellable ? 'bg-emerald-400' : 'bg-amber-300' }}"></span>
                {{ $isSellable ? __('store.available') : __('store.review_preview') }}
            </span>
        </div>

        <h3 class="mt-5 text-xl font-black tracking-tight text-white">{{ $product->name }}</h3>

        <div class="mt-auto flex items-end justify-between gap-4 pt-8">
            <div>
                @if($product->price !== null)
                    <div class="text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-600">{{ __('store.from') }}</div>
                    <div class="mt-1 text-2xl font-black text-white">
                        {{ number_format((float) $product->price, 2) }}
                        <span class="text-sm font-semibold text-slate-500">{{ $product->currency }}</span>
                    </div>
                @else
                    <div class="text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-600">{{ __('store.pricing') }}</div>
                    <div class="mt-1 text-sm font-semibold leading-5 text-slate-300">{{ __('store.pending_activation') }}</div>
                @endif
            </div>

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/[0.035] text-lg text-slate-300 transition duration-300 {{ $style['button'] }}">&rarr;</div>
        </div>
    </div>
</a>
