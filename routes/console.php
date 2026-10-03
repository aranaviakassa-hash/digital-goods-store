<?php

use App\Services\FulfillmentService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command(
    'fulfillment:reconcile-stale {--minutes=10 : Processing age in minutes before manual review}',
    function (FulfillmentService $fulfillmentService): int {
        $minutes = (int) $this->option('minutes');

        if ($minutes < 1) {
            $this->error(
                'The --minutes option must be at least 1.'
            );

            return self::FAILURE;
        }

        $reconciled =
            $fulfillmentService
                ->reconcileStaleProcessing($minutes);

        $this->info(
            "Reconciled {$reconciled} stale fulfillment attempt(s)."
        );

        return self::SUCCESS;
    }
)->purpose(
    'Move stale fulfillment attempts to manual review without retrying the supplier.'
);

Schedule::command(
    'fulfillment:reconcile-stale --minutes=10'
)
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->onOneServer();
