@php
    $name = strtolower($product->name);

    if (str_contains($name, 'pubg')) {
        $visual = [
            'label' => 'PUBG MOBILE',
            'sub' => 'UC',
            'panel' => 'from-[#211404] via-[#10141d] to-[#070a10]',
            'orb' => 'bg-amber-400/20',
            'ring' => 'border-amber-300/20',
            'text' => 'text-amber-200',
        ];
    } elseif (str_contains($name, 'free')) {
        $visual = [
            'label' => 'FREE FIRE',
            'sub' => 'DIAMONDS',
            'panel' => 'from-[#071d25] via-[#0d1320] to-[#070a10]',
            'orb' => 'bg-cyan-400/20',
            'ring' => 'border-cyan-300/20',
            'text' => 'text-cyan-200',
        ];
    } elseif (str_contains($name, 'mobile legends')) {
        $visual = [
            'label' => 'MOBILE LEGENDS',
            'sub' => 'DIAMONDS',
            'panel' => 'from-[#171027] via-[#0f1320] to-[#070a10]',
            'orb' => 'bg-violet-400/20',
            'ring' => 'border-violet-300/20',
            'text' => 'text-violet-200',
        ];
    } else {
        $visual = [
            'label' => strtoupper($product->name),
            'sub' => 'DIGITAL',
            'panel' => 'from-[#10172a] via-[#0d1320] to-[#070a10]',
            'orb' => 'bg-blue-400/20',
            'ring' => 'border-blue-300/20',
            'text' => 'text-blue-200',
        ];
    }
@endphp

<div class="relative h-full w-full overflow-hidden bg-gradient-to-br {{ $visual['panel'] }}">
    <div class="absolute inset-0 opacity-25" style="background-image:linear-gradient(rgba(255,255,255,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.035) 1px,transparent 1px);background-size:30px 30px;"></div>
    <div class="absolute -right-16 -top-16 h-52 w-52 rounded-full {{ $visual['orb'] }} blur-3xl"></div>
    <div class="absolute -bottom-20 -left-10 h-44 w-44 rounded-full bg-blue-500/10 blur-3xl"></div>

    <div class="absolute left-1/2 top-1/2 h-48 w-48 -translate-x-1/2 -translate-y-1/2 rounded-full border {{ $visual['ring'] }}"></div>
    <div class="absolute left-1/2 top-1/2 h-32 w-32 -translate-x-1/2 -translate-y-1/2 rounded-full border border-white/[0.06]"></div>

    <div class="relative flex h-full min-h-[180px] w-full items-center justify-center p-6 sm:p-8">
        <div class="text-center">
            <div class="mx-auto inline-flex min-h-8 items-center rounded-full border {{ $visual['ring'] }} bg-black/25 px-4 text-[9px] font-black tracking-[0.28em] {{ $visual['text'] }} backdrop-blur-md">
                {{ $visual['sub'] }}
            </div>
            <div class="mt-5 text-2xl font-black tracking-[-0.035em] text-white sm:text-3xl">
                {{ $visual['label'] }}
            </div>
            <div class="mx-auto mt-5 h-px w-20 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
            <img src="{{ asset('brand/playcharge-mark.svg') }}" alt="PlayCharge" class="mx-auto mt-5 h-7 w-auto opacity-65" loading="lazy">
        </div>
    </div>
</div>
