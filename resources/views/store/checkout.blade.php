@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-6xl px-5 py-16 lg:px-8">

    <div class="grid gap-8 lg:grid-cols-[1fr_420px]">

        <div>

            <div class="mb-8">

                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-violet-400">
                    Secure checkout
                </p>

                <h1 class="mt-3 text-3xl font-bold">
                    Complete your order
                </h1>

                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-400">
                    Enter the game account information carefully.
                    Digital top-ups may not be reversible after successful fulfillment.
                </p>

            </div>

            @if ($errors->any())

                <div class="mb-6 rounded-2xl border border-red-500/20 bg-red-500/10 p-5 text-sm text-red-300">

                    <div class="font-semibold">
                        Please check the information below.
                    </div>

                    <ul class="mt-3 list-disc space-y-1 pl-5">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif

            <form
                method="POST"
                action="{{ route('checkout.store', $product) }}"
                class="space-y-6"
            >

                @csrf

                <input
                    type="hidden"
                    name="idempotency_key"
                    value="{{ old('idempotency_key', (string) \Illuminate\Support\Str::uuid()) }}"
                >

                <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-6">

                    <h2 class="text-lg font-semibold">
                        Customer information
                    </h2>

                    <div class="mt-5 grid gap-5 sm:grid-cols-2">

                        <div>

                            <label
                                for="customer_name"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                Full name
                            </label>

                            <input
                                id="customer_name"
                                type="text"
                                name="customer_name"
                                value="{{ old('customer_name', auth()->user()?->name) }}"
                                autocomplete="name"
                                maxlength="255"
                                class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none transition focus:border-violet-500"
                            >

                        </div>

                        <div>

                            <label
                                for="customer_email"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                Email address
                            </label>

                            <input
                                id="customer_email"
                                type="email"
                                name="customer_email"
                                value="{{ old('customer_email', auth()->user()?->email) }}"
                                required
                                autocomplete="email"
                                maxlength="255"
                                class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none transition focus:border-violet-500"
                            >

                        </div>

                    </div>

                </div>

                <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-6">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <h2 class="text-lg font-semibold">
                                Game account information
                            </h2>

                            <p class="mt-2 text-sm leading-6 text-slate-400">
                                The exact account information required can vary by product.
                                Enter only the information requested for the receiving game account.
                            </p>

                        </div>

                        <div class="rounded-lg bg-violet-500/10 px-3 py-1 text-xs font-semibold text-violet-300">
                            Digital delivery
                        </div>

                    </div>

                    <div class="mt-6 space-y-5">

                        <div>

                            <label
                                for="account_identifier"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                Player / User ID
                                <span class="text-red-400">*</span>
                            </label>

                            <input
                                id="account_identifier"
                                type="text"
                                name="account_identifier"
                                value="{{ old('account_identifier') }}"
                                required
                                maxlength="100"
                                autocomplete="off"
                                class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none transition focus:border-violet-500"
                            >

                            <p class="mt-2 text-xs text-slate-500">
                                Enter the ID of the game account that should receive the top-up.
                            </p>

                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">

                            <div>

                                <label
                                    for="secondary_identifier"
                                    class="mb-2 block text-sm font-medium text-slate-300"
                                >
                                    Zone ID
                                    <span class="text-slate-500">
                                        (if applicable)
                                    </span>
                                </label>

                                <input
                                    id="secondary_identifier"
                                    type="text"
                                    name="secondary_identifier"
                                    value="{{ old('secondary_identifier') }}"
                                    maxlength="100"
                                    autocomplete="off"
                                    class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none transition focus:border-violet-500"
                                >

                            </div>

                            <div>

                                <label
                                    for="server_region"
                                    class="mb-2 block text-sm font-medium text-slate-300"
                                >
                                    Server / Region
                                    <span class="text-slate-500">
                                        (if applicable)
                                    </span>
                                </label>

                                <input
                                    id="server_region"
                                    type="text"
                                    name="server_region"
                                    value="{{ old('server_region') }}"
                                    maxlength="100"
                                    autocomplete="off"
                                    class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none transition focus:border-violet-500"
                                >

                            </div>

                        </div>

                        <label class="flex items-start gap-3 rounded-xl border border-amber-500/20 bg-amber-500/5 p-4">

                            <input
                                type="checkbox"
                                name="customer_data_confirmed"
                                value="1"
                                required
                                class="mt-1 h-4 w-4 rounded border-white/20 bg-slate-950 text-violet-600 focus:ring-violet-500"
                            >

                            <span class="text-sm leading-6 text-slate-300">
                                I confirm that the player/account information entered above is correct
                                and belongs to the account that should receive this digital product.
                            </span>

                        </label>

                    </div>

                </div>

                <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-6">

                    <h2 class="text-lg font-semibold">
                        Quantity
                    </h2>

                    <div class="mt-5 max-w-xs">

                        <input
                            type="number"
                            name="quantity"
                            value="{{ old('quantity', 1) }}"
                            min="1"
                            max="10"
                            required
                            class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none transition focus:border-violet-500"
                        >

                    </div>

                </div>

                <div class="space-y-4 rounded-2xl border border-white/10 bg-white/[0.03] p-6">

                    <label class="flex items-start gap-3">

                        <input
                            type="checkbox"
                            name="terms"
                            value="1"
                            required
                            class="mt-1 h-4 w-4 rounded border-white/20 bg-slate-950 text-violet-600 focus:ring-violet-500"
                        >

                        <span class="text-sm leading-6 text-slate-400">
                            I have read and agree to the

                            <a
                                href="{{ route('legal.terms') }}"
                                target="_blank"
                                class="text-violet-400 hover:text-violet-300"
                            >
                                Terms & Conditions
                            </a>.
                        </span>

                    </label>

                    <label class="flex items-start gap-3">

                        <input
                            type="checkbox"
                            name="refund_policy"
                            value="1"
                            required
                            class="mt-1 h-4 w-4 rounded border-white/20 bg-slate-950 text-violet-600 focus:ring-violet-500"
                        >

                        <span class="text-sm leading-6 text-slate-400">
                            I have read and acknowledge the

                            <a
                                href="{{ route('legal.refund') }}"
                                target="_blank"
                                class="text-violet-400 hover:text-violet-300"
                            >
                                Refund Policy
                            </a>,
                            including the conditions applicable after successful digital fulfillment.
                        </span>

                    </label>

                    <label class="flex items-start gap-3">

                        <input
                            type="checkbox"
                            name="delivery_policy"
                            value="1"
                            required
                            class="mt-1 h-4 w-4 rounded border-white/20 bg-slate-950 text-violet-600 focus:ring-violet-500"
                        >

                        <span class="text-sm leading-6 text-slate-400">
                            I have read and acknowledge the

                            <a
                                href="{{ route('legal.delivery') }}"
                                target="_blank"
                                class="text-violet-400 hover:text-violet-300"
                            >
                                Digital Delivery Policy
                            </a>.
                        </span>

                    </label>

                    <p class="border-t border-white/10 pt-4 text-xs leading-5 text-slate-500">

                        Your order information is processed according to our

                        <a
                            href="{{ route('legal.privacy') }}"
                            target="_blank"
                            class="text-slate-300 hover:text-white"
                        >
                            Privacy Policy
                        </a>.

                        Do not send card passwords, CVV codes or one-time passwords to support.

                    </p>

                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-violet-600 px-6 py-4 font-semibold text-white shadow-lg shadow-violet-600/20 transition hover:bg-violet-500"
                >
                    Continue to secure payment
                </button>

            </form>

        </div>

        <aside>

            <div class="sticky top-28 rounded-3xl border border-white/10 bg-slate-900 p-7">

                <div class="text-xs font-semibold uppercase tracking-[0.18em] text-violet-400">
                    Order summary
                </div>

                <h2 class="mt-4 text-xl font-bold">
                    {{ $product->name }}
                </h2>

                <div class="mt-2 text-sm text-slate-400">
                    {{ $product->category }}
                </div>

                <div class="my-6 h-px bg-white/10"></div>

                <div class="flex items-center justify-between">

                    <span class="text-sm text-slate-400">
                        Unit price
                    </span>

                    <span class="font-semibold">
                        {{ number_format($product->price, 2) }}
                        {{ $product->currency }}
                    </span>

                </div>

                <div class="mt-6 rounded-xl border border-emerald-500/20 bg-emerald-500/5 p-4">

                    <div class="text-sm font-semibold text-emerald-300">
                        Protected order processing
                    </div>

                    <p class="mt-2 text-xs leading-5 text-slate-400">
                        Payment confirmation and security checks are completed
                        before digital fulfillment is requested from the supplier.
                    </p>

                </div>

                <div class="mt-4 rounded-xl border border-white/10 p-4 text-xs leading-5 text-slate-500">
                    Card details are not collected on this page.
                    Payment will be handled through the approved payment provider.
                </div>

            </div>

        </aside>

    </div>

</section>

@endsection