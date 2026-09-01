<?php

namespace Tests\Feature\Student\Subscription;

use App\Models\User;
use Codenteq\Iyzico\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/student/subscriptions/';

    public function test_subscription_list()
    {
        $student = User::factory()->create();

        Subscription::create([
            'user_id' => $student->id,
            'name' => 'default',
            'iyzico_id' => 'test-ref-'.uniqid(),
            'iyzico_plan' => 'test-plan-ref',
            'iyzico_status' => 'ACTIVE',
            'iyzico_price' => 99.99,
            'base_price' => 83.33,
            'tax_price' => 16.66,
            'tax_rate' => 0.20,
        ]);

        Sanctum::actingAs($student, ['student.subscription.list']);

        $response = $this->get($this->apiUrl);

        $response->assertStatus(200);
    }

    public function test_subscription_show()
    {
        $student = User::factory()->create();

        $subscription = Subscription::create([
            'user_id' => $student->id,
            'name' => 'default',
            'iyzico_id' => 'test-ref-'.uniqid(),
            'iyzico_plan' => 'test-plan-ref',
            'iyzico_status' => 'ACTIVE',
            'iyzico_price' => 99.99,
            'base_price' => 83.33,
            'tax_price' => 16.66,
            'tax_rate' => 0.20,
        ]);

        Sanctum::actingAs($student, ['student.subscription.show']);

        $response = $this->get($this->apiUrl.$subscription->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'default']);
    }

    public function test_subscription_create_validation_error()
    {
        $student = User::factory()->create();
        Sanctum::actingAs($student, ['student.subscription.create']);

        $response = $this->postJson($this->apiUrl, []);

        $response->assertStatus(422);
    }

    public function test_subscription_list_forbidden_without_permission()
    {
        $student = User::factory()->create();
        Sanctum::actingAs($student, []);

        $response = $this->get($this->apiUrl);

        $response->assertStatus(403);
    }

    public function test_subscription_show_other_users_subscription_not_found()
    {
        $owner = User::factory()->create();
        $otherStudent = User::factory()->create();

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

        Sanctum::actingAs($otherStudent, ['student.subscription.show']);

        $response = $this->get($this->apiUrl.$subscription->id);

        $response->assertStatus(404);
    }

    public function test_subscription_create_validation_rules()
    {
        $student = User::factory()->create();
        Sanctum::actingAs($student, ['student.subscription.create']);

        $response = $this->postJson($this->apiUrl, [
            'pricing_plan_reference_code' => 'test-ref',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['card_holder_name', 'card_number', 'expire_month', 'expire_year', 'cvc']);
    }
}
