<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecuritySurfaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_cannot_open_order_without_authorization(): void
    {
        $order = $this->makeOrder();

        $this
            ->get(
                route(
                    'orders.show',
                    $order
                )
            )
            ->assertForbidden();
    }

    public function test_guest_can_open_order_after_tracking_authorizes_session(): void
    {
        $order = $this->makeOrder();

        $response =
            $this->post(
                route(
                    'orders.track.submit'
                ),
                [
                    'order_number' =>
                        $order->order_number,

                    'customer_email' =>
                        $order->customer_email,
                ]
            );

        $response->assertRedirect(
            route(
                'orders.show',
                $order
            )
        );

        $this
            ->get(
                route(
                    'orders.show',
                    $order
                )
            )
            ->assertOk();
    }

    public function test_authenticated_user_cannot_open_another_users_order(): void
    {
        $owner =
            User::factory()->create();

        $otherUser =
            User::factory()->create();

        $order =
            $this->makeOrder(
                $owner->id
            );

        $this
            ->actingAs($otherUser)
            ->get(
                route(
                    'orders.show',
                    $order
                )
            )
            ->assertForbidden();
    }

    public function test_authenticated_owner_can_open_order(): void
    {
        $user =
            User::factory()->create();

        $order =
            $this->makeOrder(
                $user->id
            );

        $this
            ->actingAs($user)
            ->get(
                route(
                    'orders.show',
                    $order
                )
            )
            ->assertOk();
    }

    public function test_password_reset_response_does_not_reveal_whether_account_exists(): void
    {
        $genericMessage =
            'If an account exists for this email, a reset link has been sent.';

        $existingUser =
            User::factory()->create([
                'email' =>
                    'existing@example.test',
            ]);

        $this
            ->post(
                route('password.email'),
                [
                    'email' =>
                        'unknown@example.test',
                ]
            )
            ->assertSessionHas(
                'status',
                $genericMessage
            );

        $this
            ->post(
                route('password.email'),
                [
                    'email' =>
                        $existingUser->email,
                ]
            )
            ->assertSessionHas(
                'status',
                $genericMessage
            );
    }

    public function test_security_headers_are_present(): void
    {
        $response =
            $this->get('/');

        $response
            ->assertHeader(
                'X-Content-Type-Options',
                'nosniff'
            )
            ->assertHeader(
                'X-Frame-Options',
                'DENY'
            )
            ->assertHeader(
                'Referrer-Policy',
                'strict-origin-when-cross-origin'
            )
            ->assertHeader(
                'Permissions-Policy',
                'camera=(), microphone=(), geolocation=()'
            )
            ->assertHeader(
                'Cross-Origin-Opener-Policy',
                'same-origin'
            );
    }

    private function makeOrder(
        ?int $userId = null
    ): Order {
        return Order::create([
            'order_number' =>
                'ORD-SEC-' .
                strtoupper(
                    fake()
                        ->unique()
                        ->bothify(
                            '????####'
                        )
                ),

            'user_id' =>
                $userId,

            'status' =>
                'pending',

            'payment_status' =>
                'unpaid',

            'fulfillment_status' =>
                'pending',

            'subtotal' =>
                '10.00',

            'total' =>
                '10.00',

            'currency' =>
                'AZN',

            'customer_email' =>
                fake()
                    ->unique()
                    ->safeEmail(),
        ]);
    }
}