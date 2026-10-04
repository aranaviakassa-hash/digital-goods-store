@php
    $name = strtolower($product->name);

    if (str_contains($name, 'pubg')) {
        $visual = [
            'asset' => 'https://www.pubgmobile.com/images/event/brandassets/down-logo1.png',
            'alt' => 'PUBG MOBILE official logo',
            'source' => 'PUBG MOBILE Brand Assets',
            'panel' => 'from-[#241800] via-[#11131b] to-[#090b11]',
            'logoClass' => 'max-h-28 max-w-[72%]',
        ];
    } elseif (str_contains($name, 'free')) {
        $visual = [
            'asset' => 'https://dl.dir.freefiremobile.com/common/web_event/official2.ff.garena.all/20229/ebcec94a33d37c7b957e8a795240f732.jpg',
            'alt' => 'FREE FIRE official logo',
            'source' => 'Garena Free Fire Brand Assets',
            'panel' => 'from-[#1b1b1b] via-[#11131b] to-[#090b11]',
            'logoClass' => 'max-h-24 max-w-[72%] rounded-md',
        ];
    } elseif (str_contains($name, 'mobile legends')) {
        $visual = [
            'asset' => 'https://en.moonton.com/upload/image/20241226/55fed3965a19e53866d0cf1279460d50.png',
            'alt' => 'Mobile Legends: Bang Bang official logo',
            'source' => 'MOONTON Games media asset',
            'panel' => 'from-[#221500] via-[#11131b] to-[#090b11]',
            'logoClass' => 'max-h-28 max-w-[82%]',
        ];
    } else {
        $visual = [
            'asset' => asset('brand/playcharge-mark.svg'),
            'alt' => $product->name,
            'source' => 'PlayCharge',
            'panel' => 'from-violet-950 via-[#11131b] to-[#090b11]',
            'logoClass' => 'max-h-24 max-w-[55%]',
        ];
    }
@endphp

<div class="relative h-full w-full overflow-hidden bg-gradient-to-br {{ $visual['panel'] }}">
    <div
        class="absolute inset-0 opacity-20"
        style="background-image:linear-gradient(rgba(255,255,255,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.035) 1px,transparent 1px);background-size:28px 28px;"
    ></div>

    <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full bg-white/10 blur-3xl"></div>

    <div class="relative flex h-full w-full items-center justify-center p-6">
        <img
            src="{{ $visual['asset'] }}"
            alt="{{ $visual['alt'] }}"
            class="h-auto w-auto object-contain drop-shadow-[0_18px_35px_rgba(0,0,0,.48)] {{ $visual['logoClass'] }}"
            loading="lazy"
            referrerpolicy="no-referrer"
        >
    </div>

    <div class="absolute bottom-3 right-3 rounded-full border border-white/10 bg-black/35 px-2.5 py-1 text-[9px] font-semibold uppercase tracking-[0.12em] text-white/45 backdrop-blur">
        Official game logo
    </div>
</div>
