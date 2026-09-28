<?php

namespace App\Http\Controllers;

use App\Models\Product;

class StoreController extends Controller
{
    public function home()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->where('resale_verified', true)
            ->where('bank_approved', true)
            ->latest()
            ->take(6)
            ->get();

        return view('store.home', compact('products'));
    }

    public function products()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->where('resale_verified', true)
            ->where('bank_approved', true)
            ->latest()
            ->paginate(12);

        return view('store.products', compact('products'));
    }

    public function product(Product $product)
    {
        abort_unless(
            $product->is_active
            && $product->resale_verified
            && $product->bank_approved,
            404
        );

        return view('store.product', compact('product'));
    }
}