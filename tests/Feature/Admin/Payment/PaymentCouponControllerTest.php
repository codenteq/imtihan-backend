<?php

namespace Tests\Feature\Admin\Payment;

use App\Models\PaymentCoupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PaymentCouponControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/payment/coupons/';

    public function test_payment_coupon_list()
    {
        PaymentCoupon::factory(20)->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.payment-coupon.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(10, 'data');
    }

    public function test_payment_coupon_create()
    {
        $paymentCoupon = PaymentCoupon::factory()->make();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.payment-coupon.create']);

        $response = $this->postJson($this->apiUrl, $paymentCoupon->toArray());
        $response->assertStatus(201);
    }

    public function test_payment_coupon_show()
    {
        $paymentCoupon = PaymentCoupon::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.payment-coupon.show']);

        $response = $this->get($this->apiUrl.$paymentCoupon->id);
        $response->assertJsonFragment(['id' => $paymentCoupon->id]);
    }

    public function test_payment_coupon_update()
    {
        $paymentCoupon = PaymentCoupon::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.payment-coupon.update']);

        $response = $this->putJson($this->apiUrl.$paymentCoupon->id, [
            'name' => 'test',
        ]);
        $response->assertStatus(200);
    }

    public function test_payment_coupon_delete()
    {
        $paymentCoupon = PaymentCoupon::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.payment-coupon.delete']);

        $response = $this->delete($this->apiUrl.$paymentCoupon->id);
        $response->assertStatus(200);
    }
}
