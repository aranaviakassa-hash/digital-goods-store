@extends('layouts.store')

@section('content')

@php
    $locale = app()->getLocale();

    $page =
        config("legal.pages.{$pageKey}.{$locale}")
        ?? config("legal.pages.{$pageKey}.az");
@endphp

<section class="relative overflow-hidden">

    <div class="absolute inset-0 -z-20 bg-[#070A12]"></div>

    <div
        class="absolute inset-x-0 top-0 -z-10 h-[420px]"
        style="
            background:
                radial-gradient(circle at 20% 0%, rgba(6,182,212,.10), transparent 35%),
                radial-gradient(circle at 80% 0%, rgba(124,58,237,.14), transparent 35%);
        "
    ></div>

    <div class="mx-auto max-w-5xl px-5 py-20 lg:px-8 lg:py-24">

        <div class="max-w-3xl">

            <p class="text-xs font-bold uppercase tracking-[0.22em] text-violet-400">
                {{ $page['eyebrow'] }}
            </p>

            <h1 class="mt-4 text-4xl font-black tracking-tight text-white sm:text-5xl">
                {{ $page['title'] }}
            </h1>

            <p class="mt-6 text-base leading-8 text-slate-400">
                {{ $page['intro'] }}
            </p>

            <div class="mt-5 text-xs text-slate-600">
                {{ app()->getLocale() === 'az'
                    ? 'Son yenilənmə'
                    : (app()->getLocale() === 'ru'
                        ? 'Последнее обновление'
                        : 'Last updated') }}:
                {{ config('legal.last_updated') }}
            </div>

        </div>

        <div class="mt-12 space-y-5">

            @foreach($page['sections'] as $section)

                <article class="rounded-3xl border border-white/[0.08] bg-white/[0.025] p-6 sm:p-8">

                    <h2 class="text-lg font-bold text-white sm:text-xl">
                        {{ $section['title'] }}
                    </h2>

                    <div class="mt-4 space-y-4">

                        @foreach($section['paragraphs'] as $paragraph)

                            <p class="text-sm leading-7 text-slate-400">
                                {{ $paragraph }}
                            </p>

                        @endforeach

                    </div>

                </article>

            @endforeach

        </div>

        <div class="mt-8 rounded-3xl border border-cyan-400/10 bg-cyan-400/[0.04] p-6">

            <div class="text-sm font-semibold text-white">
                {{ config('company.legal_short_name') }}
            </div>

            <div class="mt-3 grid gap-2 text-xs leading-5 text-slate-500 sm:grid-cols-3">

                <div>
                    VÖEN: {{ config('company.tax_id') }}
                </div>

                <div>
                    {{ config('company.legal_address') }}
                </div>

                <div>
                    {{ config('company.phone_display') }}
                </div>

            </div>

        </div>

    </div>

</section>

@endsection