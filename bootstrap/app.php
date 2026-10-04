<?php

use App\Http\Middleware\MaskSensitiveInput;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(
    basePath: dirname(__DIR__)
)
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',

        then: function (): void {
            Route::middleware('api')
                ->prefix('webhooks')
                ->name('webhooks.')
                ->group(
                    base_path('routes/webhooks.php')
                );
        },
    )
    ->withMiddleware(
        function (Middleware $middleware): void {
            /*
             * The public review site is exposed through Cloudflare Tunnel while
             * the Laravel origin remains bound to localhost. Trust forwarded
             * scheme/host information so Laravel correctly detects public HTTPS.
             */
            $middleware->trustProxies(at: '*');

            $middleware->web(
                append: [
                    SetLocale::class,
                    SecurityHeaders::class,
                    MaskSensitiveInput::class,
                ],
            );
        }
    )
    ->withExceptions(
        function (Exceptions $exceptions): void {
            $exceptions->shouldRenderJsonWhen(
                fn (Request $request) =>
                    $request->is('api/*')
                    || $request->is('webhooks/*')
                    || $request->expectsJson(),
            );
        }
    )
    ->create();
