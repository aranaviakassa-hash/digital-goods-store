@extends('layouts.store')

@section('content')

<div class="mx-auto max-w-3xl">

    <h1 class="text-3xl font-bold">
        Order {{ $order->order_number }}
    </h1>

    <div class="mt-6 rounded-xl border bg-white p-6">

        <p>
            <strong>Email:</strong>
            {{ $order->customer_email }}
        </p>

        <p class="mt-3">
            <strong>Payment:</strong>
            {{ ucfirst($order->payment_status) }}
        </p>

        <p class="mt-3">
            <strong>Fulfillment:</strong>
            {{ ucfirst(str_replace('_', ' ', $order->fulfillment_status)) }}
        </p>

        <p class="mt-3">
            <strong>Status:</strong>
            {{ ucfirst($order->status) }}
        </p>

        <hr class="my-6">

        @foreach($order->items as $item)
            <div class="flex justify-between py-2">
                <span>
                    {{ $item->product_name }} × {{ $item->quantity }}
                </span>

                <span>
                    {{ number_format($item->total_price, 2) }}
                    {{ $item->currency }}
                </span>
            </div>
        @endforeach

        <div class="mt-6 flex justify-between border-t pt-4 text-lg font-bold">
            <span>Total</span>

            <span>
                {{ number_format($order->total, 2) }}
                {{ $order->currency }}
            </span>
        </div>

    </div>
</div>

@endsection