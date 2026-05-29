<?php

namespace Tests\Feature\Admin\Subscription;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionPlanTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/subscription/plans/';

    public function test_plan_create_validation_error()
    {
        $admin = User::factory()->create();
        Sanctum::actingAs($admin, ['admin.subscription.plan.create']);

        $response = $this->postJson($this->apiUrl, []);

        $response->assertStatus(422);
    }

    public function test_plan_create_validation_rules()
    {
        $admin = User::factory()->create();
        Sanctum::actingAs($admin, ['admin.subscription.plan.create']);

        $response = $this->postJson($this->apiUrl, [
            'product_reference_code' => 'test-ref',
            'name' => 'Monthly Plan',
            'price' => 29.99,
            'currency_code' => 'INVALID',
            'payment_interval' => 'MONTHLY',
            'plan_payment_type' => 'RECURRING',
            'payment_interval_count' => 1,
            'recurrence_count' => 12,
            'trial_period_days' => 0,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['currency_code']);
    }

    public function test_plan_create_forbidden_without_permission()
    {
        $admin = User::factory()->create();
        Sanctum::actingAs($admin, []);

        $response = $this->postJson($this->apiUrl, [
            'product_reference_code' => 'test-ref',
            'name' => 'Monthly Plan',
            'price' => 29.99,
            'currency_code' => 'TRY',
            'payment_interval' => 'MONTHLY',
            'plan_payment_type' => 'RECURRING',
            'payment_interval_count' => 1,
            'recurrence_count' => 12,
        ]);

        $response->assertStatus(403);
    }
}
