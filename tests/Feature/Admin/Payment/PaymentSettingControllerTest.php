<?php

namespace Tests\Feature\Admin\Payment;

use App\Models\PaymentSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PaymentSettingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/payment/settings/';

    public function test_payment_setting_list()
    {
        PaymentSetting::factory(20)->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.payment-setting.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(10, 'data');
    }

    public function test_payment_setting_create()
    {
        $paymentSetting = PaymentSetting::factory()->make();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.payment-setting.create']);

        $response = $this->postJson($this->apiUrl, $paymentSetting->toArray());
        $response->assertStatus(201);
    }

    public function test_payment_setting_show()
    {
        $paymentSetting = PaymentSetting::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.payment-setting.show']);

        $response = $this->get($this->apiUrl.$paymentSetting->id);
        $response->assertJsonFragment(['id' => $paymentSetting->id]);
    }

    public function test_payment_setting_update()
    {
        $paymentSetting = PaymentSetting::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.payment-setting.update']);

        $response = $this->putJson($this->apiUrl.$paymentSetting->id, [
            'price' => 100,
            'is_default' => true,
        ]);
        $response->assertStatus(200);
    }

    public function test_payment_setting_delete()
    {
        $paymentSetting = PaymentSetting::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.payment-setting.delete']);

        $response = $this->delete($this->apiUrl.$paymentSetting->id);
        $response->assertStatus(200);
    }
}
