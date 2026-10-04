@extends('layouts.store')

@section('content')
@php
    $copy = match(app()->getLocale()) {
        'az' => ['title'=>'Yeni şifrə seç','email'=>'Email','password'=>'Yeni şifrə','confirm'=>'Şifrəni təsdiqlə','button'=>'Şifrəni yenilə'],
        'ru' => ['title'=>'Выберите новый пароль','email'=>'Email','password'=>'Новый пароль','confirm'=>'Подтвердите пароль','button'=>'Обновить пароль'],
        default => ['title'=>'Choose a new password','email'=>'Email','password'=>'New password','confirm'=>'Confirm password','button'=>'Reset password'],
    };
@endphp

<section class="mx-auto flex min-h-[65vh] max-w-7xl items-center justify-center px-5 py-16">
    <div class="w-full max-w-md">
        <div class="text-center"><img src="{{ asset('brand/playcharge-mark.svg') }}" alt="PlayCharge" class="mx-auto h-12 w-12"><h1 class="mt-5 text-3xl font-black">{{ $copy['title'] }}</h1></div>
        <form method="POST" action="{{ route('password.update') }}" class="pc-panel mt-8 space-y-5 rounded-[1.75rem] p-6">@csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div><label for="email" class="text-sm text-slate-300">{{ $copy['email'] }}</label><input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email" class="mt-2 w-full rounded-xl border border-white/10 bg-[#080a14] px-4 py-3 text-white outline-none focus:border-violet-500"></div>
            <div><label for="password" class="text-sm text-slate-300">{{ $copy['password'] }}</label><input id="password" type="password" name="password" required autocomplete="new-password" class="mt-2 w-full rounded-xl border border-white/10 bg-[#080a14] px-4 py-3 text-white outline-none focus:border-violet-500"></div>
            <div><label for="password_confirmation" class="text-sm text-slate-300">{{ $copy['confirm'] }}</label><input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="mt-2 w-full rounded-xl border border-white/10 bg-[#080a14] px-4 py-3 text-white outline-none focus:border-violet-500"></div>
            @if($errors->any())<div class="rounded-xl border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-300">{{ $errors->first() }}</div>@endif
            <button class="pc-button w-full rounded-xl py-3.5 font-bold text-white">{{ $copy['button'] }}</button>
        </form>
    </div>
</section>
@endsection
