<?php

namespace Tests\Feature\Admin\Condition;

use App\Models\Condition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ConditionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/condition/conditions/';

    public function test_condition_list()
    {
        Condition::factory(20)->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.condition.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(10, 'data');
    }

    public function test_condition_create()
    {
        $condition = Condition::factory()->make();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.condition.create']);

        $response = $this->postJson($this->apiUrl, $condition->toArray());
        $response->assertStatus(201);
    }

    public function test_condition_show()
    {
        $condition = Condition::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.condition.show']);

        $response = $this->get($this->apiUrl.$condition->id);
        $response->assertJsonFragment(['id' => $condition->id]);
    }

    public function test_condition_update()
    {
        $condition = Condition::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.condition.update']);

        $response = $this->putJson($this->apiUrl.$condition->id, [
            'name' => 'test',
        ]);
        $response->assertStatus(200);
    }

    public function test_condition_delete()
    {
        $condition = Condition::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.condition.delete']);

        $response = $this->delete($this->apiUrl.$condition->id);
        $response->assertStatus(200);
    }
}
