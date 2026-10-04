<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BankReviewCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_visible_review_product_can_open_non_transactional_preview(): void
    {
        config()->set('company.live_payment_enabled', false);

        $product = Product::create([
            'name' => 'Free Fire Diamonds',
            'slug' => 'free-fire-review-preview',
            'category' => 'Direct Top-Up',
            'supplier' => null,
            'supplier_product_code' => null,
            'price' => null,
            'currency' => 'AZN',
            'catalog_visible' => true,
            'is_active' => false,
            'resale_verified' => false,
            'bank_approved' => false,
            'description' => 'Review preview product.',
        ]);

        $this->get(route('checkout.review', $product))
            ->assertOk()
            ->assertSee('Checkout preview');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_hidden_product_cannot_open_review_checkout(): void
    {
        config()->set('company.live_payment_enabled', false);

        $product = Product::create([
            'name' => 'Hidden Review Product',
            'slug' => 'hidden-review-product',
            'category' => 'Direct Top-Up',
            'supplier' => null,
            'supplier_product_code' => null,
            'price' => null,
            'currency' => 'AZN',
            'catalog_visible' => false,
            'is_active' => false,
            'resale_verified' => false,
            'bank_approved' => false,
            'description' => 'Hidden product.',
        ]);

        $this->get(route('checkout.review', $product))
            ->assertNotFound();
    }

    public function test_review_checkout_is_disabled_when_live_payments_are_enabled(): void
    {
        config()->set('company.live_payment_enabled', true);

        $product = Product::create([
            'name' => 'Live Product',
            'slug' => 'live-product-review-route',
            'category' => 'Direct Top-Up',
            'supplier' => 'supplier',
            'supplier_product_code' => 'LIVE-1',
            'price' => '10.00',
            'currency' => 'AZN',
            'catalog_visible' => true,
            'is_active' => true,
            'resale_verified' => true,
            'bank_approved' => true,
            'description' => 'Live product.',
        ]);

        $this->get(route('checkout.review', $product))
            ->assertNotFound();
    }
}
