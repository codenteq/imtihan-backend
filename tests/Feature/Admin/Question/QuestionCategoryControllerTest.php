<?php

namespace Tests\Feature\Admin\Question;

use App\Models\QuestionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class QuestionCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/question/categories/';

    public function test_question_category_list()
    {
        QuestionCategory::factory(20)->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.question.category.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(10, 'data');
    }

    public function test_question_category_create()
    {
        $questionCategory = QuestionCategory::factory()->make();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.question.category.create']);

        $response = $this->postJson($this->apiUrl, $questionCategory->toArray());
        $response->assertStatus(201);
    }

    public function test_question_category_show()
    {
        $questionCategory = QuestionCategory::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.question.category.show']);

        $response = $this->get($this->apiUrl.$questionCategory->id);
        $response->assertJsonFragment(['id' => $questionCategory->id]);
    }

    public function test_question_category_update()
    {
        $questionCategory = QuestionCategory::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.question.category.update']);

        $response = $this->putJson($this->apiUrl.$questionCategory->id, [
            'name' => 'test',
            'description' => 'test',
        ]);
        $response->assertStatus(200);
    }

    public function test_question_category_delete()
    {
        $questionCategory = QuestionCategory::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.question.category.delete']);

        $response = $this->deleteJson($this->apiUrl.$questionCategory->id);
        $response->assertStatus(200);
    }
}
