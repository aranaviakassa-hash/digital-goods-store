<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SecurityReview;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SecurityReviewService
{
    public function startReview(Order $order): SecurityReview
    {
        if ($order->payment_status !== 'paid') {
            throw new RuntimeException(
                'Only paid orders can enter security review.'
            );
        }

        return SecurityReview::firstOrCreate(
            ['order_id' => $order->id],
            [
                'status' => 'pending',
                'risk_score' => 0,
                'risk_flags' => [],
            ]
        );
    }

    public function approve(
        SecurityReview $review,
        ?string $notes = null
    ): SecurityReview {
        return DB::transaction(function () use ($review, $notes) {
            $review->update([
                'status' => 'approved',
                'review_notes' => $notes,
                'reviewed_at' => now(),
            ]);

            $review->order->update([
                'fulfillment_status' => 'processing',
            ]);

            return $review->fresh();
        });
    }

    public function reject(
        SecurityReview $review,
        ?string $notes = null
    ): SecurityReview {
        return DB::transaction(function () use ($review, $notes) {
            $review->update([
                'status' => 'rejected',
                'review_notes' => $notes,
                'reviewed_at' => now(),
            ]);

            $review->order->update([
                'status' => 'cancelled',
                'fulfillment_status' => 'failed',
            ]);

            return $review->fresh();
        });
    }
}