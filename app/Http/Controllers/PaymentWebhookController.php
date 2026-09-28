<?php

namespace App\Http\Controllers;

use App\Models\PaymentAttempt;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentWebhookController extends Controller
{
    public function handle(
        Request $request,
        OrderService $orderService
    ): Response {
        $providerPaymentId = $request->string('provider_payment_id')->toString();
        $status = $request->string('status')->toString();

        $attempt = PaymentAttempt::query()
            ->where('provider_payment_id', $providerPaymentId)
            ->first();

        if (! $attempt) {
            return response('Payment attempt not found', 404);
        }

        if ($status === 'paid') {
            $orderService->markPaymentPaid(
                $attempt,
                $providerPaymentId,
                $request->all()
            );
        }

        if ($status === 'failed') {
            $orderService->markPaymentFailed(
                $attempt,
                $request->all()
            );
        }

        return response('OK');
    }
}