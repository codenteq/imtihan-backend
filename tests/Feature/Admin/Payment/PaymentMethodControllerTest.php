<?php

namespace Tests\Feature\Admin\Payment;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PaymentMethodControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/payment/methods/';

    public function test_payment_method_list()
    {
        PaymentMethod::factory(20)->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.payment-method.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(20);
    }

    public function test_payment_method_create()
    {
        $paymentMethod = PaymentMethod::factory()->make();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.payment-method.create']);

        $response = $this->postJson($this->apiUrl, $paymentMethod->toArray());
        $response->assertStatus(201);
    }

    public function test_payment_method_show()
    {
        $paymentMethod = PaymentMethod::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.payment-method.show']);

        $response = $this->get($this->apiUrl.$paymentMethod->id);
        $response->assertJsonFragment(['id' => $paymentMethod->id]);
    }

    public function test_payment_method_update()
    {
        $paymentMethod = PaymentMethod::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.payment-method.update']);

        $response = $this->putJson($this->apiUrl.$paymentMethod->id, [
            'name' => 'Test',
        ]);
        $response->assertStatus(200);
    }

    public function test_payment_method_delete()
    {
        $paymentMethod = PaymentMethod::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.payment-method.delete']);

        $response = $this->deleteJson($this->apiUrl.$paymentMethod->id);
        $response->assertStatus(200);
    }
}
