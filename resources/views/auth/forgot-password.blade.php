@extends('layouts.store')

@section('content')
@php
    $copy = match(app()->getLocale()) {
        'az' => ['title'=>'Şifrəni sıfırla','intro'=>'Email ünvanını daxil et, sıfırlama linkini göndərək.','email'=>'Email ünvanı','button'=>'Sıfırlama linkini göndər'],
        'ru' => ['title'=>'Сбросить пароль','intro'=>'Введите email, и мы отправим ссылку для сброса пароля.','email'=>'Email','button'=>'Отправить ссылку'],
        default => ['title'=>'Reset your password','intro'=>"Enter your email and we'll send you a reset link.",'email'=>'Email address','button'=>'Send reset link'],
    };
@endphp

<section class="mx-auto flex min-h-[65vh] max-w-7xl items-center justify-center px-5 py-16">
    <div class="w-full max-w-md">
        <div class="text-center">
            <img src="{{ asset('brand/playcharge-mark.svg') }}" alt="PlayCharge" class="mx-auto h-12 w-12">
            <h1 class="mt-5 text-3xl font-black">{{ $copy['title'] }}</h1>
            <p class="mt-3 text-sm text-slate-400">{{ $copy['intro'] }}</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="pc-panel mt-8 space-y-5 rounded-[1.75rem] p-6">@csrf
            <div><label for="email" class="text-sm text-slate-300">{{ $copy['email'] }}</label><input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="mt-2 w-full rounded-xl border border-white/10 bg-[#080a14] px-4 py-3 text-white outline-none focus:border-violet-500"></div>
            @if(session('status'))<div class="rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-emerald-300">{{ session('status') }}</div>@endif
            @if($errors->any())<div class="rounded-xl border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-300">{{ $errors->first() }}</div>@endif
            <button class="pc-button w-full rounded-xl py-3.5 font-bold text-white">{{ $copy['button'] }}</button>
        </form>
    </div>
</section>
@endsection
