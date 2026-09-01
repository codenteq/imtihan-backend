<?php

namespace Tests\Feature\Admin\Subscription;

use App\Models\User;
use Codenteq\Iyzico\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/subscription/subscriptions/';

    public function test_subscription_list()
    {
        $owner = User::factory()->create();

        Subscription::create([
            'user_id' => $owner->id,
            'name' => 'default',
            'iyzico_id' => 'test-ref-'.uniqid(),
            'iyzico_plan' => 'test-plan-ref',
            'iyzico_status' => 'ACTIVE',
            'iyzico_price' => 99.99,
            'base_price' => 83.33,
            'tax_price' => 16.66,
            'tax_rate' => 0.20,
        ]);

        $admin = User::factory()->create();
        Sanctum::actingAs($admin, ['admin.subscription.list']);

        $response = $this->get($this->apiUrl);

        $response->assertStatus(200);
    }

    public function test_subscription_show()
    {
        $owner = User::factory()->create();

        $subscription = Subscription::create([
            'user_id' => $owner->id,
            'name' => 'default',
            'iyzico_id' => 'test-ref-'.uniqid(),
            'iyzico_plan' => 'test-plan-ref',
            'iyzico_status' => 'ACTIVE',
            'iyzico_price' => 99.99,
            'base_price' => 83.33,
            'tax_price' => 16.66,
            'tax_rate' => 0.20,
        ]);

        $admin = User::factory()->create();
        Sanctum::actingAs($admin, ['admin.subscription.show']);

        $response = $this->get($this->apiUrl.$subscription->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'default']);
    }

    public function test_subscription_list_forbidden_without_permission()
    {
        $admin = User::factory()->create();
        Sanctum::actingAs($admin, []);

        $response = $this->get($this->apiUrl);

        $response->assertStatus(403);
    }

    public function test_subscription_show_not_found()
    {
        $admin = User::factory()->create();
        Sanctum::actingAs($admin, ['admin.subscription.show']);

        $response = $this->get($this->apiUrl.Str::uuid());

        $response->assertStatus(404);
    }
}
