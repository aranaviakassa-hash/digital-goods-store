@php
    $name = strtolower($product->name);
    $isSellable = $product->isSellable();

    $style = str_contains($name, 'pubg')
        ? [
            'border' => 'hover:border-amber-400/40',
            'gradient' => 'from-amber-500/20 via-orange-500/10 to-transparent',
            'badge' => 'bg-amber-400/10 text-amber-300 border-amber-400/20',
            'button' => 'group-hover:bg-amber-500 group-hover:text-black',
            'image' => asset('games/pubg-mobile.svg'),
        ]
        : (
            str_contains($name, 'free')
            ? [
                'border' => 'hover:border-cyan-400/40',
                'gradient' => 'from-cyan-500/20 via-blue-500/10 to-transparent',
                'badge' => 'bg-cyan-400/10 text-cyan-300 border-cyan-400/20',
                'button' => 'group-hover:bg-cyan-400 group-hover:text-black',
                'image' => asset('games/free-fire.svg'),
            ]
            : [
                'border' => 'hover:border-pink-400/40',
                'gradient' => 'from-violet-500/20 via-pink-500/10 to-transparent',
                'badge' => 'bg-pink-400/10 text-pink-300 border-pink-400/20',
                'button' => 'group-hover:bg-pink-400 group-hover:text-black',
                'image' => asset('games/mobile-legends.svg'),
            ]
        );
@endphp

<a
    href="{{ route('products.show', $product) }}"
    class="group relative overflow-hidden rounded-[1.6rem] border border-white/[0.08] bg-[#0C101B] transition duration-300 hover:-translate-y-1 {{ $style['border'] }} hover:shadow-2xl hover:shadow-black/30"
>
    <div class="relative h-44 overflow-hidden bg-gradient-to-br {{ $style['gradient'] }}">
        <img
            src="{{ $style['image'] }}"
            alt="{{ $product->name }}"
            class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.035]"
            loading="lazy"
        >
        <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-[#0C101B] to-transparent"></div>
        <div class="absolute right-4 top-4 rounded-full border border-white/10 bg-black/35 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.16em] text-white/80 backdrop-blur">
            PlayCharge
        </div>
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

        <h3 class="mt-5 text-xl font-bold text-white">
            {{ $product->name }}
        </h3>

        <div class="mt-6 flex items-end justify-between gap-4">
            <div>
                @if($product->price !== null)
                    <div class="text-[11px] uppercase tracking-wider text-slate-600">
                        {{ __('store.from') }}
                    </div>
                    <div class="mt-1 text-2xl font-black">
                        {{ number_format((float) $product->price, 2) }}
                        <span class="text-sm font-semibold text-slate-500">
                            {{ $product->currency }}
                        </span>
                    </div>
                @else
                    <div class="text-[11px] uppercase tracking-wider text-slate-600">
                        Pricing
                    </div>
                    <div class="mt-1 max-w-44 text-sm font-semibold leading-5 text-slate-300">
                        Pending activation
                    </div>
                @endif
            </div>

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/[0.04] text-lg transition duration-300 {{ $style['button'] }}">
                &rarr;
            </div>
        </div>
    </div>
</a>