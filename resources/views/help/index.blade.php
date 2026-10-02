@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-5xl px-5 py-16 lg:px-8">

    <div class="text-center">

        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-violet-400">
            Support
        </p>

        <h1 class="mt-3 text-4xl font-black">
            Help Center
        </h1>

        <p class="mx-auto mt-4 max-w-2xl text-slate-400">
            Quick answers about orders, payments, delivery and refunds.
        </p>

    </div>

    <div class="mt-12 space-y-4">

        @php
            $faqs = [
                [
                    'q' => 'Where can I see my order?',
                    'a' => 'Use My Account if you are signed in, or Track Order using the order number and the email used during checkout.',
                ],
                [
                    'q' => 'My payment was successful but the order is still processing. What should I do?',
                    'a' => 'Some orders require payment verification or a security review before digital fulfillment begins. Check the order status page before contacting support.',
                ],
                [
                    'q' => 'How are digital products delivered?',
                    'a' => 'Approved orders are fulfilled digitally. Delivery details depend on the selected product and supplier.',
                ],
                [
                    'q' => 'Can I get a refund?',
                    'a' => 'A failed order that has not been successfully fulfilled may be reviewed for refund or replacement. Successfully delivered digital products may be irreversible.',
                ],
                [
                    'q' => 'I entered incorrect information. What happens?',
                    'a' => 'Contact support immediately. Digital fulfillment may become irreversible once completed, so customers should verify account and product information before payment.',
                ],
                [
                    'q' => 'Why can an order be delayed?',
                    'a' => 'An order may be delayed for payment verification, fraud prevention, manual security review, supplier availability or technical checks.',
                ],
            ];
        @endphp

        @foreach($faqs as $faq)

            <details class="group rounded-2xl border border-white/10 bg-slate-900">

                <summary class="flex cursor-pointer list-none items-center justify-between gap-5 p-6 font-semibold">

                    {{ $faq['q'] }}

                    <span class="text-violet-400 transition group-open:rotate-45">
                        +
                    </span>

                </summary>

                <div class="px-6 pb-6 text-sm leading-7 text-slate-400">
                    {{ $faq['a'] }}
                </div>

            </details>

        @endforeach

    </div>

    <div class="mt-10 rounded-2xl border border-violet-500/20 bg-violet-500/10 p-7 text-center">

        <h2 class="text-xl font-bold">
            Still need help?
        </h2>

        <p class="mt-2 text-sm text-slate-400">
            Contact support and include your order number.
        </p>

        <a
            href="{{ route('contact') }}"
            class="mt-5 inline-block rounded-xl bg-violet-600 px-6 py-3 text-sm font-semibold hover:bg-violet-500"
        >
            Contact Support
        </a>

    </div>

</section>

@endsection