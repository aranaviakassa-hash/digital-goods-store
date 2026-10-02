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
        $request->request->remove('card_number');
        $request->request->remove('pan');
        $request->request->remove('cvv');
        $request->request->remove('cvc');
        $request->request->remove('otp');
        $request->request->remove('password_confirmation');

        return $next($request);
    }
}