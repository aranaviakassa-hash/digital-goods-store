<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaskSensitiveInput
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        /*
         * This application must never collect raw card data.
         *
         * If these fields somehow reach the application,
         * discard them before application code can use them.
         *
         * Do NOT remove password/password_confirmation:
         * Laravel validation and password reset flows require them.
         */
        foreach ([
            'card_number',
            'pan',
            'cvv',
            'cvc',
            'card_cvv',
            'card_cvc',
            'otp',
        ] as $field) {
            $request->request->remove($field);
        }

        return $next($request);
    }
}