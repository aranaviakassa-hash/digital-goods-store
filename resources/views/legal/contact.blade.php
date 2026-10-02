@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-5xl px-5 py-20 lg:px-8">

    <div class="max-w-3xl">

        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-violet-400">
            Support
        </p>

        <h1 class="mt-3 text-4xl font-bold">
            Contact & Business Information
        </h1>

        <p class="mt-5 leading-7 text-slate-400">
            For questions about orders, payments, delivery or account
            access, you can contact our support team using the details below.
        </p>

    </div>

    <div class="mt-10 grid gap-6 md:grid-cols-2">

        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-7">

            <h2 class="text-lg font-semibold">
                Customer Support
            </h2>

            <div class="mt-5 space-y-4 text-sm">

                <div>
                    <div class="text-slate-500">Email</div>

                    <a
                        href="mailto:{{ config('company.support_email') }}"
                        class="mt-1 block text-slate-200 hover:text-violet-300"
                    >
                        {{ config('company.support_email') }}
                    </a>
                </div>

                <div>
                    <div class="text-slate-500">Phone</div>

                    <a
                        href="tel:{{ config('company.phone') }}"
                        class="mt-1 block text-slate-200 hover:text-violet-300"
                    >
                        {{ config('company.phone') }}
                    </a>
                </div>

                <div>
                    <div class="text-slate-500">Support hours</div>

                    <div class="mt-1 text-slate-200">
                        {{ config('company.support_hours') }}
                    </div>
                </div>

            </div>

        </div>

        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-7">

            <h2 class="text-lg font-semibold">
                Business Information
            </h2>

            <div class="mt-5 space-y-4 text-sm">

                <div>
                    <div class="text-slate-500">Legal name</div>

                    <div class="mt-1 text-slate-200">
                        {{ config('company.legal_name') }}
                    </div>
                </div>

                <div>
                    <div class="text-slate-500">VÖEN</div>

                    <div class="mt-1 text-slate-200">
                        {{ config('company.tax_id') }}
                    </div>
                </div>

                <div>
                    <div class="text-slate-500">Registered activity</div>

                    <div class="mt-1 text-slate-200">
                        {{ config('company.activity') }}
                    </div>
                </div>

                <div>
                    <div class="text-slate-500">Legal address</div>

                    <div class="mt-1 text-slate-200">
                        {{ config('company.legal_address') }}
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div class="mt-6 rounded-2xl border border-white/10 bg-slate-900 p-7">

        <h2 class="text-lg font-semibold">
            Order support
        </h2>

        <p class="mt-3 text-sm leading-6 text-slate-400">
            When contacting support about an existing purchase,
            include your order number and the email address used
            when placing the order. Never send card passwords,
            CVV codes, one-time passwords or internet banking credentials.
        </p>

        <a
            href="{{ route('orders.track') }}"
            class="mt-5 inline-flex rounded-xl bg-violet-600 px-5 py-3 text-sm font-semibold transition hover:bg-violet-500"
        >
            Track an order
        </a>

    </div>

</section>

@endsection