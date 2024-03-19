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

    public function test_question_category_tree_list()
    {
        $questionCategory = QuestionCategory::factory(1)->state(['parent_id' => null])->create();
        $childrenCategory = QuestionCategory::factory(1)->state(['parent_id' => $questionCategory->first()->id])->create();

        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.question.category.list']);

        $response = $this->get($this->apiUrl . 'tree');

        info($questionCategory);
        info($childrenCategory);
        info($response->json());

        $response->assertStatus(200)
            ->assertExactJson([
                [
                    'id' => $questionCategory->first()->id,
                    'name' => $questionCategory->first()->name,
                    'description' => $questionCategory->first()->description,
                    'parent_id' => $questionCategory->first()->parent_id,
                    'language_id' => $questionCategory->first()->language_id,
                    'created_at' => $questionCategory->first()->created_at,
                    'updated_at' => $questionCategory->first()->updated_at,
                    'deleted_at' => $questionCategory->first()->deleted_at,
                    'children_tree' => [
                        [
                            'id' => $childrenCategory->first()->id,
                            'name' => $childrenCategory->first()->name,
                            'description' => $childrenCategory->first()->description,
                            'parent_id' => $childrenCategory->first()->parent_id,
                            'language_id' => $childrenCategory->first()->language_id,
                            'created_at' => $childrenCategory->first()->created_at,
                            'updated_at' => $childrenCategory->first()->updated_at,
                            'deleted_at' => $childrenCategory->first()->deleted_at,
                            'children_tree' => []
                        ]
                    ]
                ]
            ]);
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

        $response = $this->get($this->apiUrl . $questionCategory->id);
        $response->assertJsonFragment(['id' => $questionCategory->id]);
    }

    public function test_question_category_update()
    {
        $questionCategory = QuestionCategory::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.question.category.update']);

        $response = $this->putJson($this->apiUrl . $questionCategory->id, [
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

        $response = $this->deleteJson($this->apiUrl . $questionCategory->id);
        $response->assertStatus(200);
    }
}
