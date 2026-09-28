<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SecurityReview;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SecurityReviewService
{
    public function __construct(
        protected AuditLogService $auditLogService
    ) {
    }

    public function startReview(Order $order): SecurityReview
    {
        if ($order->payment_status !== 'paid') {
            throw new RuntimeException(
                'Only paid orders can enter security review.'
            );
        }

        $review = SecurityReview::firstOrCreate(
            [
                'order_id' => $order->id,
            ],
            [
                'status' => 'pending',
                'risk_score' => 0,
                'risk_flags' => [],
            ]
        );

        $order->update([
            'fulfillment_status' => 'security_review',
        ]);

        $this->auditLogService->log(
            'security_review.started',
            $order,
            [
                'security_review_id' => $review->id,
            ]
        );

        return $review;
    }

    public function approve(
        SecurityReview $review,
        ?string $notes = null
    ): SecurityReview {
        return DB::transaction(function () use ($review, $notes) {
            if ($review->status === 'approved') {
                return $review;
            }

            $review->update([
                'status' => 'approved',
                'review_notes' => $notes,
                'reviewed_at' => now(),
            ]);

            $review->order->update([
                'fulfillment_status' => 'processing',
            ]);

            $this->auditLogService->log(
                'security_review.approved',
                $review->order,
                [
                    'security_review_id' => $review->id,
                    'notes' => $notes,
                ]
            );

            return $review->fresh();
        });
    }

    public function reject(
        SecurityReview $review,
        ?string $notes = null
    ): SecurityReview {
        return DB::transaction(function () use ($review, $notes) {
            if ($review->status === 'rejected') {
                return $review;
            }

            $review->update([
                'status' => 'rejected',
                'review_notes' => $notes,
                'reviewed_at' => now(),
            ]);

            $review->order->update([
                'status' => 'cancelled',
                'fulfillment_status' => 'failed',
            ]);

            $this->auditLogService->log(
                'security_review.rejected',
                $review->order,
                [
                    'security_review_id' => $review->id,
                    'notes' => $notes,
                ]
            );

            return $review->fresh();
        });
    }
}