<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show(Product $product)
    {
        abort_unless(
            $product->is_active
            && $product->resale_verified
            && $product->bank_approved,
            404
        );

        return view('store.checkout', compact('product'));
    }

    public function store(
        Request $request,
        Product $product,
        OrderService $orderService
    ) {
        abort_unless(
            $product->is_active
            && $product->resale_verified
            && $product->bank_approved,
            404
        );

        $validated = $request->validate([
            'customer_email' => [
                'required',
                'email',
                'max:255',
            ],
            'customer_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:10',
            ],
            'terms' => [
                'accepted',
            ],
        ]);

        $order = $orderService->createOrder([
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'customer_email' => $validated['customer_email'],
            'customer_name' => $validated['customer_name'] ?? null,
            'user_id' => auth()->id(),
            'idempotency_key' => 'CHECKOUT-' . Str::uuid(),
        ]);

        return redirect()->route('orders.show', [
            'order' => $order->order_number,
        ]);
    }

    public function success(Order $order)
    {
        $order->load('items');

        return view('store.order', compact('order'));
    }

    public function trackingForm()
    {
        return view('store.track');
    }

    public function tracking(Request $request)
    {
        $validated = $request->validate([
            'order_number' => [
                'required',
                'string',
            ],
            'customer_email' => [
                'required',
                'email',
            ],
        ]);

        $order = Order::query()
            ->where('order_number', $validated['order_number'])
            ->where('customer_email', $validated['customer_email'])
            ->first();

        if (! $order) {
            return back()->withErrors([
                'order_number' =>
                    'Order not found. Check the order number and email.',
            ]);
        }

        return redirect()->route('orders.show', [
            'order' => $order->order_number,
        ]);
    }
}