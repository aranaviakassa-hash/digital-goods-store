<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderEvidence;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(Product $product): View
    {
        $this->ensureProductIsSellable($product);

        return view('store.checkout', [
            'product' => $product,
        ]);
    }

    public function store(
        Request $request,
        Product $product,
        OrderService $orderService
    ): RedirectResponse {
        /*
         * Never trust the product state that was shown to the
         * customer earlier. Re-check sellability server-side
         * immediately before creating the order.
         */
        $this->ensureProductIsSellable($product);

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

            'account_identifier' => [
                'required',
                'string',
                'max:100',
            ],

            'secondary_identifier' => [
                'nullable',
                'string',
                'max:100',
            ],

            'server_region' => [
                'nullable',
                'string',
                'max:100',
            ],

            'idempotency_key' => [
                'required',
                'uuid',
            ],

            'terms' => [
                'accepted',
            ],

            'refund_policy' => [
                'accepted',
            ],

            'delivery_policy' => [
                'accepted',
            ],

            'customer_data_confirmed' => [
                'accepted',
            ],
        ]);

        $order = DB::transaction(function () use (
            $validated,
            $request,
            $product,
            $orderService
        ) {
            /*
             * A product may have changed state between initial
             * validation and this transaction. Reload the current
             * database state before order creation.
             */
            $product->refresh();

            $this->ensureProductIsSellable(
                $product
            );

            $order = $orderService->createOrder([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'customer_email' =>
                    strtolower(
                        trim(
                            $validated['customer_email']
                        )
                    ),
                'customer_name' =>
                    filled(
                        $validated['customer_name']
                        ?? null
                    )
                        ? trim(
                            $validated['customer_name']
                        )
                        : null,
                'idempotency_key' =>
                    $validated['idempotency_key'],
            ]);

            if (
                auth()->check()
                && ! $order->user_id
            ) {
                $order->user_id =
                    auth()->id();

                $order->save();
            }

            $order->load('items');

            $orderItem =
                $order->items->first();

            abort_unless(
                $orderItem !== null,
                500,
                'Order item could not be created.'
            );

            $orderItem->delivery_data = [
                'account_identifier' =>
                    trim(
                        $validated[
                            'account_identifier'
                        ]
                    ),

                'secondary_identifier' =>
                    filled(
                        $validated[
                            'secondary_identifier'
                        ] ?? null
                    )
                        ? trim(
                            $validated[
                                'secondary_identifier'
                            ]
                        )
                        : null,

                'server_region' =>
                    filled(
                        $validated[
                            'server_region'
                        ] ?? null
                    )
                        ? trim(
                            $validated[
                                'server_region'
                            ]
                        )
                        : null,

                'customer_confirmed' =>
                    true,
            ];

            $orderItem->save();

            $now = now();

            OrderEvidence::updateOrCreate(
                [
                    'order_id' =>
                        $order->id,
                ],
                [
                    'terms_version' =>
                        config(
                            'policies.terms.version'
                        ),

                    'refund_policy_version' =>
                        config(
                            'policies.refund.version'
                        ),

                    'delivery_policy_version' =>
                        config(
                            'policies.delivery.version'
                        ),

                    'privacy_policy_version' =>
                        config(
                            'policies.privacy.version'
                        ),

                    'terms_accepted_at' =>
                        $now,

                    'refund_policy_accepted_at' =>
                        $now,

                    'delivery_policy_accepted_at' =>
                        $now,

                    'customer_data_confirmed_at' =>
                        $now,

                    'ip_address' =>
                        $request->ip(),

                    'user_agent' =>
                        $request->userAgent(),

                    'locale' =>
                        app()->getLocale(),
                ]
            );

            return $order;
        });

        $request->session()->put(
            "allowed_order_ids.{$order->id}",
            true
        );

        return redirect()
            ->route(
                'orders.show',
                $order
            );
    }

    public function success(
        Request $request,
        Order $order
    ): View {
        $ownsOrder =
            auth()->check()
            && (int) $order->user_id
                === (int) auth()->id();

        $guestAccess =
            (bool) $request
                ->session()
                ->get(
                    "allowed_order_ids.{$order->id}",
                    false
                );

        abort_unless(
            $ownsOrder || $guestAccess,
            403
        );

        $order->load([
            'items',
            'evidence',
        ]);

        return view(
            'store.order',
            [
                'order' => $order,
            ]
        );
    }

    public function trackingForm(): View
    {
        return view(
            'store.track'
        );
    }

    public function tracking(
        Request $request
    ): RedirectResponse {
        $validated =
            $request->validate([
                'order_number' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'customer_email' => [
                    'required',
                    'email',
                    'max:255',
                ],
            ]);

        $order =
            Order::query()
                ->where(
                    'order_number',
                    trim(
                        $validated[
                            'order_number'
                        ]
                    )
                )
                ->whereRaw(
                    'LOWER(customer_email) = ?',
                    [
                        strtolower(
                            trim(
                                $validated[
                                    'customer_email'
                                ]
                            )
                        ),
                    ]
                )
                ->first();

        if (! $order) {
            return back()
                ->withErrors([
                    'order_number' =>
                        'No order was found with the provided information.',
                ])
                ->withInput();
        }

        $request->session()->put(
            "allowed_order_ids.{$order->id}",
            true
        );

        return redirect()
            ->route(
                'orders.show',
                $order
            );
    }

    private function ensureProductIsSellable(
        Product $product
    ): void {
        abort_unless(
            $product->isSellable(),
            404
        );
    }
}