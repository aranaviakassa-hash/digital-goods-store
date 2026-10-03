<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $response = $next($request);

        $response->headers->set(
            'X-Content-Type-Options',
            'nosniff'
        );

        $response->headers->set(
            'X-Frame-Options',
            'DENY'
        );

        $response->headers->set(
            'Referrer-Policy',
            'strict-origin-when-cross-origin'
        );

        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=()'
        );

        $response->headers->set(
            'Cross-Origin-Opener-Policy',
            'same-origin'
        );

        $scriptSources = [
            "'self'",
            "'unsafe-inline'",
        ];

        $connectSources = [
            "'self'",
        ];

        /*
         * Laravel Vite development server compatibility.
         * Production remains limited to same-origin resources.
         */
        if (! app()->environment('production')) {
            $scriptSources[] = 'http://127.0.0.1:5173';
            $scriptSources[] = 'http://localhost:5173';

            $connectSources[] = 'http://127.0.0.1:5173';
            $connectSources[] = 'http://localhost:5173';
            $connectSources[] = 'ws://127.0.0.1:5173';
            $connectSources[] = 'ws://localhost:5173';
        }

        $directives = [
            "default-src 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
            "object-src 'none'",
            "img-src 'self' data: blob: https:",
            "font-src 'self' data:",
            "style-src 'self' 'unsafe-inline'",
            'script-src ' . implode(' ', $scriptSources),
            'connect-src ' . implode(' ', $connectSources),
            "media-src 'self'",
            "manifest-src 'self'",
            "worker-src 'self' blob:",
        ];

        if (
            app()->environment('production')
            && $request->isSecure()
        ) {
            $directives[] = 'upgrade-insecure-requests';

            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        $response->headers->set(
            'Content-Security-Policy',
            implode('; ', $directives)
        );

        return $response;
    }
}
