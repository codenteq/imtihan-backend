<?php

namespace Tests\Feature\Admin\ExamType;

use App\Models\ExamType;
use App\Models\QuestionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExamTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/exam-types';

    public function test_exam_type_list()
    {
        ExamType::factory(20)->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.exam-type.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(10, 'data');
    }

    public function test_exam_type_create(): void
    {
        $user = User::factory()->create();
        $examType = ExamType::factory()->make();
        $examTypeCategory = collect();

        $questionCategory = QuestionCategory::factory(10)->create();

        $questionCategory->map(function ($category) use ($examTypeCategory) {
            $examTypeCategory->push($category->id);
        });

        Sanctum::actingAs($user, ['admin.exam-type.create']);

        $response = $this->postJson($this->apiUrl, [
            'name' => $examType->name,
            'language_id' => $examType->language_id,
            'question_categories' => $examTypeCategory,
        ]);

        $response->assertStatus(201);
    }

    public function test_exam_type_show()
    {
        $examType = ExamType::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.exam-type.show']);

        $response = $this->get($this->apiUrl . '/' . $examType->id);

        $response->assertJsonFragment(['id' => $examType->id]);
    }

    public function test_exam_type_update()
    {
        $examType = ExamType::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.exam-type.update']);

        $response = $this->putJson($this->apiUrl . '/' . $examType->id, [
            'name' => 'Test',
        ]);

        $response->assertStatus(200)->assertJson(['name' => 'Test']);
    }

    public function test_exam_type_delete()
    {
        $examType = ExamType::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.exam-type.delete']);

        $response = $this->delete($this->apiUrl . '/' . $examType->id);
        $response->assertStatus(200);
    }
}
