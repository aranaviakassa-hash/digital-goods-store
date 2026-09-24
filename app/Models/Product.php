<?php

namespace App\Models;

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
        'resale_verified',
        'bank_approved',
        'description',
    ];
}