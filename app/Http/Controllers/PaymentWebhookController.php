<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentAttempt;
use App\Services\AuditLogService;
use App\Services\OrderService;
use App\Services\SecurityReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    public function handle(
        Request $request,
        OrderService $orderService,
        SecurityReviewService $securityReviewService,
        AuditLogService $auditLogService
    ): JsonResponse {
        $configuredSecret = config('payments.webhook_secret');
        $receivedSecret = $request->header('X-Webhook-Secret');

        if (
            blank($configuredSecret)
            || blank($receivedSecret)
            || ! hash_equals($configuredSecret, $receivedSecret)
        ) {
            return response()->json([
                'message' => 'Invalid webhook signature.',
            ], 401);
        }

        $data = $request->validate([
            'order_number' => ['required', 'string'],
            'provider_payment_id' => ['required', 'string'],
            'status' => ['required', 'in:paid,failed'],
        ]);

        $order = Order::query()
            ->where('order_number', $data['order_number'])
            ->first();

        if (! $order) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        }

        $attempt = PaymentAttempt::query()
            ->where('order_id', $order->id)
            ->latest('id')
            ->first();

        if (! $attempt) {
            return response()->json([
                'message' => 'Payment attempt not found.',
            ], 404);
        }

        if (
            $attempt->status === 'paid'
            && $data['status'] === 'paid'
        ) {
            return response()->json([
                'message' => 'Already processed.',
            ]);
        }

        if ($data['status'] === 'paid') {
            $orderService->markPaymentPaid(
                $attempt,
                $data['provider_payment_id'],
                $request->all()
            );

            $auditLogService->log(
                'payment.paid',
                $order,
                [
                    'payment_attempt_id' => $attempt->id,
                    'provider_payment_id' =>
                        $data['provider_payment_id'],
                ]
            );

            $securityReviewService->startReview(
                $order->fresh()
            );
        }

        if (
            $data['status'] === 'failed'
            && $attempt->status !== 'paid'
        ) {
            $orderService->markPaymentFailed(
                $attempt,
                $request->all()
            );

            $auditLogService->log(
                'payment.failed',
                $order,
                [
                    'payment_attempt_id' => $attempt->id,
                ]
            );
        }

        return response()->json([
            'message' => 'OK',
        ]);
    }
}