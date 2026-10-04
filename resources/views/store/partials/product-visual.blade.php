@php
    $name = strtolower($product->name);

    if (str_contains($name, 'pubg')) {
        $visual = [
            'asset' => 'https://www.pubgmobile.com/images/event/brandassets/down-logo1.png',
            'alt' => 'PUBG MOBILE official logo',
            'panel' => 'from-[#2a1b00] via-[#12141c] to-[#080a10]',
            'logoClass' => 'w-[76%] max-w-[460px] max-h-[72%]',
            'frameClass' => '',
        ];
    } elseif (str_contains($name, 'free')) {
        $visual = [
            'asset' => 'https://dl.dir.freefiremobile.com/common/web_event/official2.ff.garena.all/20229/ebcec94a33d37c7b957e8a795240f732.jpg',
            'alt' => 'FREE FIRE official logo',
            'panel' => 'from-[#444444] via-[#343434] to-[#272727]',
            'logoClass' => 'w-[84%] max-w-[520px] max-h-[78%] scale-[1.08]',
            'frameClass' => 'rounded-xl shadow-2xl shadow-black/35',
        ];
    } elseif (str_contains($name, 'mobile legends')) {
        $visual = [
            'asset' => 'https://en.moonton.com/upload/image/20241226/55fed3965a19e53866d0cf1279460d50.png',
            'alt' => 'Mobile Legends: Bang Bang official logo',
            'panel' => 'from-[#271800] via-[#12141c] to-[#080a10]',
            'logoClass' => 'w-[82%] max-w-[520px] max-h-[74%]',
            'frameClass' => '',
        ];
    } else {
        $visual = [
            'asset' => asset('brand/playcharge-mark.svg'),
            'alt' => $product->name,
            'panel' => 'from-violet-950 via-[#11131b] to-[#090b11]',
            'logoClass' => 'w-[42%] max-w-[220px] max-h-[60%]',
            'frameClass' => '',
        ];
    }
@endphp

<div class="relative h-full w-full overflow-hidden bg-gradient-to-br {{ $visual['panel'] }}">
    <div
        class="absolute inset-0 opacity-[0.16]"
        style="background-image:linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px);background-size:32px 32px;"
    ></div>

    <div class="absolute left-1/2 top-1/2 h-2/3 w-2/3 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/[0.055] blur-3xl"></div>

    <div class="relative flex h-full w-full items-center justify-center p-5 sm:p-7">
        <img
            src="{{ $visual['asset'] }}"
            alt="{{ $visual['alt'] }}"
            class="h-auto object-contain drop-shadow-[0_18px_35px_rgba(0,0,0,.48)] {{ $visual['logoClass'] }} {{ $visual['frameClass'] }}"
            loading="lazy"
            referrerpolicy="no-referrer"
        >
    </div>
</div>
