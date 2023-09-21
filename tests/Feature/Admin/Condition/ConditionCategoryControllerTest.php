<?php

namespace Tests\Feature\Admin\Condition;

use App\Models\ConditionCategory;
use App\Models\Language;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ConditionCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/condition/categories/';

    public function test_condition_category_list()
    {
        ConditionCategory::factory(20)->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.condition-category.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(10, 'data');
    }

    public function test_condition_category_create()
    {
        $conditionCategory = ConditionCategory::factory()->make();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.condition-category.create']);

        $response = $this->postJson($this->apiUrl, $conditionCategory->toArray());
        $response->assertStatus(201);
    }

    public function test_condition_category_show()
    {
        $conditionCategory = ConditionCategory::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.condition-category.show']);

        $response = $this->get($this->apiUrl.$conditionCategory->id);
        $response->assertJsonFragment(['id' => $conditionCategory->id]);
    }

    public function test_condition_category_update()
    {
        $conditionCategory = ConditionCategory::factory()->create();
        $language = Language::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.condition-category.update']);

        $response = $this->putJson($this->apiUrl.$conditionCategory->id, [
            'name' => 'test',
            'key' => 'test',
            'language_id' => $language->id,
        ]);
        $response->assertStatus(200);
    }

    public function test_condition_category_delete()
    {
        $conditionCategory = ConditionCategory::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.condition-category.delete']);

        $response = $this->delete($this->apiUrl.$conditionCategory->id);
        $response->assertStatus(200);
    }
}
