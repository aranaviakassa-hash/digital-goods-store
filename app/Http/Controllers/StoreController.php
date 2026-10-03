<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

class StoreController extends Controller
{
    public function home(): View
    {
        $products =
            Product::query()
                ->visibleInCatalog()
                ->orderByRaw("
                    CASE
                        WHEN LOWER(name) LIKE '%pubg%' THEN 1
                        WHEN LOWER(name) LIKE '%free fire%' THEN 2
                        WHEN LOWER(name) LIKE '%mobile legends%' THEN 3
                        ELSE 100
                    END
                ")
                ->orderBy('name')
                ->take(6)
                ->get();

        return view(
            'store.home',
            compact('products')
        );
    }

    public function products(): View
    {
        $products =
            Product::query()
                ->visibleInCatalog()
                ->orderByRaw("
                    CASE
                        WHEN LOWER(name) LIKE '%pubg%' THEN 1
                        WHEN LOWER(name) LIKE '%free fire%' THEN 2
                        WHEN LOWER(name) LIKE '%mobile legends%' THEN 3
                        ELSE 100
                    END
                ")
                ->orderBy('name')
                ->paginate(12);

        return view(
            'store.products',
            compact('products')
        );
    }

    public function product(
        Product $product
    ): View {
        abort_unless(
            $product->catalog_visible,
            404
        );

        return view(
            'store.product',
            compact('product')
        );
    }
}