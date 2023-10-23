<?php

namespace Tests\Feature\Student\Exam;

use App\Enums\Role;
use App\Models\Condition;
use App\Models\ConditionCategory;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\QuestionOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExamControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/student/exams/';

    public function test_exam_list()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        Exam::factory(20)->state(['user_id' => $user->id])->create();

        Sanctum::actingAs($user, ['student.exam.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(20);
    }

    public function test_exam_create()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();

        $category = QuestionCategory::factory()->create();
        $question = Question::factory()->state(['category_id' => $category->id])->create();
        QuestionOption::factory(4)->for($question)->create();

        $condition = ConditionCategory::factory()->state(['key' => 'length', 'value' => 1])->create();
        Condition::factory()->state(['name' => 'length', 'question_category_id' => $condition->id, 'condition_category_id' => $condition->id])->create();

        Sanctum::actingAs($user, ['student.exam.create']);

        $response = $this->postJson($this->apiUrl, [
            'type' => 'normal',
        ]);

        $response->assertStatus(201);
    }
}
