<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_visible_product_is_shown_even_if_not_sellable(): void
    {
        $product = Product::create([
            'name' => 'PUBG Mobile UC',
            'slug' => 'pubg-mobile-uc-review',
            'category' => 'Direct Top-Up',
            'supplier' => null,
            'supplier_product_code' => null,
            'price' => '10.00',
            'currency' => 'AZN',

            'catalog_visible' => true,

            'is_active' => false,
            'resale_verified' => false,
            'bank_approved' => false,

            'description' =>
                'Review-only catalogue product.',
        ]);

        $this
            ->get(route('products.index'))
            ->assertOk()
            ->assertSee($product->name);

        $this
            ->get(
                route(
                    'products.show',
                    $product
                )
            )
            ->assertOk()
            ->assertSee($product->name);
    }

    public function test_catalog_hidden_product_is_not_accessible_publicly(): void
    {
        $product = Product::create([
            'name' => 'Hidden Product',
            'slug' => 'hidden-product',
            'category' => 'Direct Top-Up',
            'supplier' => null,
            'supplier_product_code' => null,
            'price' => '10.00',
            'currency' => 'AZN',

            'catalog_visible' => false,

            'is_active' => true,
            'resale_verified' => true,
            'bank_approved' => true,

            'description' =>
                'Hidden product.',
        ]);

        $this
            ->get(
                route(
                    'products.show',
                    $product
                )
            )
            ->assertNotFound();

        $this
            ->get(route('products.index'))
            ->assertDontSee(
                $product->name
            );
    }

    public function test_visible_but_not_sellable_product_cannot_open_checkout(): void
    {
        $product = Product::create([
            'name' => 'Mobile Legends Review Product',
            'slug' => 'mobile-legends-review',
            'category' => 'Direct Top-Up',
            'supplier' => null,
            'supplier_product_code' => null,
            'price' => '12.00',
            'currency' => 'AZN',

            'catalog_visible' => true,

            'is_active' => false,
            'resale_verified' => false,
            'bank_approved' => false,

            'description' =>
                'Visible to bank reviewers but not available for checkout.',
        ]);

        $this
            ->get(
                route(
                    'checkout.show',
                    $product
                )
            )
            ->assertNotFound();
    }

    public function test_fully_approved_product_can_open_checkout(): void
    {
        $product = Product::create([
            'name' => 'Approved Top-Up',
            'slug' => 'approved-top-up',
            'category' => 'Direct Top-Up',
            'supplier' => 'test-supplier',
            'supplier_product_code' => 'TEST-001',
            'price' => '15.00',
            'currency' => 'AZN',

            'catalog_visible' => true,

            'is_active' => true,
            'resale_verified' => true,
            'bank_approved' => true,

            'description' =>
                'Approved checkout product.',
        ]);

        $this
            ->get(
                route(
                    'checkout.show',
                    $product
                )
            )
            ->assertOk();
    }
}