@extends('layouts.store')

@section('content')
@php
    $copy = match(app()->getLocale()) {
        'az' => ['title'=>'Email ünvanını təsdiqlə','text'=>'Təsdiqləmə linkini bu ünvana göndərmişik:','after'=>'Hesaba giriş üçün email-dəki linki aç və ünvanını təsdiqlə.','button'=>'Təsdiqləmə emailini yenidən göndər'],
        'ru' => ['title'=>'Подтвердите email','text'=>'Мы отправили ссылку подтверждения на:','after'=>'Откройте письмо и подтвердите адрес, чтобы получить доступ к аккаунту.','button'=>'Отправить письмо повторно'],
        default => ['title'=>'Verify your email','text'=>"We've sent a verification link to:",'after'=>'Open the email and confirm your address to access your account.','button'=>'Resend verification email'],
    };
@endphp

<section class="mx-auto flex min-h-[65vh] max-w-7xl items-center justify-center px-5 py-16">
    <div class="pc-panel w-full max-w-lg rounded-[2rem] p-8 text-center shadow-2xl shadow-black/20">
        <img src="{{ asset('brand/playcharge-mark.svg') }}" alt="PlayCharge" class="mx-auto h-14 w-14">
        <h1 class="mt-6 text-3xl font-black">{{ $copy['title'] }}</h1>
        <p class="mt-4 leading-7 text-slate-400">{{ $copy['text'] }} <strong class="text-white">{{ auth()->user()->email }}</strong>. {{ $copy['after'] }}</p>
        @if(session('status'))<div class="mt-5 rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-emerald-300">{{ session('status') }}</div>@endif
        <form method="POST" action="{{ route('verification.send') }}" class="mt-7">@csrf<button class="pc-button rounded-xl px-6 py-3 font-bold text-white">{{ $copy['button'] }}</button></form>
    </div>
</section>
@endsection
