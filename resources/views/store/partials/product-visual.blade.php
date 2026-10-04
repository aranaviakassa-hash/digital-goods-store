@php
    $name = strtolower($product->name);

    if (str_contains($name, 'pubg')) {
        $visual = [
            'asset' => asset('games/pubg-mobile.svg'),
            'alt' => 'PUBG Mobile top-up artwork',
            'accent' => 'amber',
            'border' => 'border-amber-400/20',
            'glow' => 'from-amber-500/25 via-orange-500/10 to-transparent',
        ];
    } elseif (str_contains($name, 'free')) {
        $visual = [
            'asset' => asset('games/free-fire.svg'),
            'alt' => 'Free Fire top-up artwork',
            'accent' => 'cyan',
            'border' => 'border-cyan-400/20',
            'glow' => 'from-cyan-500/25 via-blue-500/10 to-transparent',
        ];
    } elseif (str_contains($name, 'mobile legends')) {
        $visual = [
            'asset' => asset('games/mobile-legends.svg'),
            'alt' => 'Mobile Legends top-up artwork',
            'accent' => 'pink',
            'border' => 'border-pink-400/20',
            'glow' => 'from-violet-500/25 via-pink-500/10 to-transparent',
        ];
    } else {
        $visual = [
            'asset' => asset('brand/playcharge-mark.svg'),
            'alt' => $product->name,
            'accent' => 'violet',
            'border' => 'border-violet-400/20',
            'glow' => 'from-violet-500/25 via-fuchsia-500/10 to-transparent',
        ];
    }
@endphp

<div class="relative h-full w-full overflow-hidden bg-gradient-to-br {{ $visual['glow'] }}">
    <div
        class="absolute inset-0 opacity-25"
        style="background-image:linear-gradient(rgba(255,255,255,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.035) 1px,transparent 1px);background-size:28px 28px;"
    ></div>

    <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full bg-white/10 blur-3xl"></div>

    <div class="relative flex h-full w-full items-center justify-center p-4">
        <img
            src="{{ $visual['asset'] }}"
            alt="{{ $visual['alt'] }}"
            class="h-full w-full object-contain drop-shadow-[0_18px_35px_rgba(0,0,0,.42)]"
            loading="lazy"
        >
    </div>
</div>
