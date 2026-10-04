@php
    $name = strtolower($product->name);

    if (str_contains($name, 'pubg')) {
        $visual = [
            'label' => 'PUBG MOBILE',
            'sub' => 'UC TOP-UP',
            'panel' => 'from-[#2a1b00] via-[#12141c] to-[#080a10]',
            'accent' => 'text-amber-300 border-amber-400/20 bg-amber-400/10',
        ];
    } elseif (str_contains($name, 'free')) {
        $visual = [
            'label' => 'FREE FIRE',
            'sub' => 'DIAMONDS',
            'panel' => 'from-[#20160d] via-[#12141c] to-[#080a10]',
            'accent' => 'text-cyan-300 border-cyan-400/20 bg-cyan-400/10',
        ];
    } elseif (str_contains($name, 'mobile legends')) {
        $visual = [
            'label' => 'MOBILE LEGENDS',
            'sub' => 'DIAMONDS',
            'panel' => 'from-[#271800] via-[#12141c] to-[#080a10]',
            'accent' => 'text-pink-300 border-pink-400/20 bg-pink-400/10',
        ];
    } else {
        $visual = [
            'label' => strtoupper($product->name),
            'sub' => 'DIGITAL PRODUCT',
            'panel' => 'from-violet-950 via-[#11131b] to-[#090b11]',
            'accent' => 'text-violet-300 border-violet-400/20 bg-violet-400/10',
        ];
    }
@endphp

<div class="relative h-full w-full overflow-hidden bg-gradient-to-br {{ $visual['panel'] }}">
    <div
        class="absolute inset-0 opacity-[0.14]"
        style="background-image:linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px);background-size:32px 32px;"
    ></div>

    <div class="absolute left-1/2 top-1/2 h-2/3 w-2/3 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/[0.055] blur-3xl"></div>

    <div class="relative flex h-full w-full items-center justify-center p-5 sm:p-7">
        <div class="text-center">
            <div class="mx-auto inline-flex rounded-2xl border px-4 py-2 text-[10px] font-black tracking-[0.28em] {{ $visual['accent'] }}">
                {{ $visual['sub'] }}
            </div>
            <div class="mt-4 text-2xl font-black tracking-tight text-white sm:text-3xl">
                {{ $visual['label'] }}
            </div>
            <img
                src="{{ asset('brand/playcharge-mark.svg') }}"
                alt="PlayCharge"
                class="mx-auto mt-5 h-8 w-auto opacity-70"
                loading="lazy"
            >
        </div>
    </div>
</div>
