<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'catalog_visible' => 'boolean',
        'resale_verified' => 'boolean',
        'bank_approved' => 'boolean',
    ];

    public function scopeVisibleInCatalog(
        Builder $query
    ): Builder {
        return $query
            ->where(
                'catalog_visible',
                true
            );
    }

    public function scopeSellable(
        Builder $query
    ): Builder {
        return $query
            ->where(
                'catalog_visible',
                true
            )
            ->where(
                'is_active',
                true
            )
            ->where(
                'resale_verified',
                true
            )
            ->where(
                'bank_approved',
                true
            )
            ->whereNotNull(
                'price'
            );
    }

    public function isSellable(): bool
    {
        return
            $this->catalog_visible
            && $this->is_active
            && $this->resale_verified
            && $this->bank_approved
            && $this->price !== null;
    }
}