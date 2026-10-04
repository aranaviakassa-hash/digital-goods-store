<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category',
        'supplier',
        'supplier_product_code',
        'price',
        'currency',
        'is_active',
        'catalog_visible',
        'resale_verified',
        'bank_approved',
        'resale_verification_reference',
        'bank_approval_reference',
        'approval_notes',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'catalog_visible' => 'boolean',
        'resale_verified' => 'boolean',
        'bank_approved' => 'boolean',
        'resale_verified_at' => 'datetime',
        'bank_approved_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::updating(function (Product $product): void {
            $actorId = Auth::guard('admin')->id() ?? Auth::guard('web')->id();

            if ($product->isDirty('resale_verified')) {
                $product->resale_verified_at = $product->resale_verified ? now() : null;
                $product->resale_verified_by = $product->resale_verified ? $actorId : null;
            }

            if ($product->isDirty('bank_approved')) {
                $product->bank_approved_at = $product->bank_approved ? now() : null;
                $product->bank_approved_by = $product->bank_approved ? $actorId : null;
            }
        });

        static::updated(function (Product $product): void {
            $approvalFields = [
                'resale_verified',
                'bank_approved',
                'resale_verification_reference',
                'bank_approval_reference',
                'approval_notes',
            ];

            $changed = array_values(array_filter(
                $approvalFields,
                fn (string $field): bool => $product->wasChanged($field)
            ));

            if ($changed === []) {
                return;
            }

            AuditLog::create([
                'event' => 'product.approval_changed',
                'auditable_type' => self::class,
                'auditable_id' => $product->id,
                'user_id' => Auth::guard('admin')->id() ?? Auth::guard('web')->id(),
                'context' => [
                    'changed_fields' => $changed,
                    'resale_verified' => $product->resale_verified,
                    'bank_approved' => $product->bank_approved,
                    'resale_verification_reference' => $product->resale_verification_reference,
                    'bank_approval_reference' => $product->bank_approval_reference,
                ],
            ]);
        });
    }

    public function scopeVisibleInCatalog(
        Builder $query
    ): Builder {
        return $query->where('catalog_visible', true);
    }

    public function scopeSellable(
        Builder $query
    ): Builder {
        return $query
            ->where('catalog_visible', true)
            ->where('is_active', true)
            ->where('resale_verified', true)
            ->where('bank_approved', true)
            ->whereNotNull('price')
            ->where('price', '>', 0);
    }

    public function isSellable(): bool
    {
        return $this->catalog_visible
            && $this->is_active
            && $this->resale_verified
            && $this->bank_approved
            && $this->price !== null
            && (float) $this->price > 0;
    }

    public function isPurchasableNow(): bool
    {
        return $this->isSellable()
            && (bool) config('company.live_payment_enabled');
    }
}
