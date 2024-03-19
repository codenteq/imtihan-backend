<?php

namespace Tests\Feature\Student\Exam;

use App\Enums\Role;
use App\Models\Condition;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\ExamTypeCategory;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\QuestionOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
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

    public function test_normal_exam_create()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();

        $examType = ExamType::factory()->create();

        $category = QuestionCategory::factory()->create();
        $question = Question::factory()->state(['category_id' => $category->id])->create();
        QuestionOption::factory(4)->for($question)->create();

        $categoryTwo = QuestionCategory::factory()->create();
        $questionTwo = Question::factory()->state(['category_id' => $categoryTwo->id])->create();
        QuestionOption::factory(4)->for($question)->create();

        $examTypeCategory = ExamTypeCategory::factory()->state([
            'exam_type_id' => $examType->id,
            'question_category_id' => $category->id,
        ])->create();

        $examTypeCategoryTwo = ExamTypeCategory::factory()->state([
            'exam_type_id' => $examType->id,
            'question_category_id' => $categoryTwo->id,
        ])->create();

        Condition::factory()->state([
            'name' => 'Exam Time',
            'exam_type_id' => $examType->id,
            'exam_type_category_id' => null,
            'condition_category' => \App\Enums\ConditionCategory::Time->value,
            'value' => 15,
        ])->create();

        Condition::factory()->state([
            'name' => 'length',
            'exam_type_id' => $examType->id,
            'exam_type_category_id' => $examTypeCategory->id,
            'condition_category' => \App\Enums\ConditionCategory::Length->value,
            'value' => 1,
        ])->create();

        Condition::factory()->state([
            'name' => 'length',
            'exam_type_id' => $examType->id,
            'exam_type_category_id' => $examTypeCategoryTwo->id,
            'condition_category' => \App\Enums\ConditionCategory::Length->value,
            'value' => 1,
        ])->create();

        Sanctum::actingAs($user, ['student.exam.create']);

        $response = $this->postJson($this->apiUrl, [
            'type' => 'normal',
            'id' => $examType->id,
        ]);

        $response->assertStatus(201);
    }

    public function test_custom_exam_create()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();

        $category = QuestionCategory::factory()->create();
        $question = Question::factory()->state(['category_id' => $category->id])->create();
        QuestionOption::factory(4)->for($question)->create();

        Sanctum::actingAs($user, ['student.exam.create']);

        $response = $this->postJson($this->apiUrl, [
            'type' => 'custom',
            'id' => $category->id,
        ]);

        $response->assertStatus(201);
    }

    /*
     * Exam type is normal
     */
    public function test_normal_exam_answer()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();

        $category = QuestionCategory::factory()->create();

        $examType = ExamType::factory()->state(['name' => 'TYT'])->create();

        $examTypeCategory = ExamTypeCategory::factory()->state([
            'exam_type_id' => $examType->id,
            'question_category_id' => $category->id,
        ])->create();

        $exam = Exam::factory()->state([
            'user_id' => $user->id,
            'exam_type_id' => $examType->id,
        ])->create();

        Condition::factory()->state([
            'name' => 'Question Length',
            'condition_category' => \App\Enums\ConditionCategory::Length->value,
            'exam_type_id' => $examType->id,
            'exam_type_category_id' => $examTypeCategory->id,
            'value' => 10,
            'is_active' => true,
        ])->create();

        Condition::factory()->state([
            'name' => 'Exam Time',
            'condition_category' => \App\Enums\ConditionCategory::Time->value,
            'exam_type_id' => $examType->id,
            'exam_type_category_id' => null,
            'value' => 15,
            'is_active' => true,
        ])->create();

        Condition::factory()->state([
            'name' => 'Exam Penalty Ratio',
            'condition_category' => \App\Enums\ConditionCategory::PenaltyRatio->value,
            'exam_type_id' => $examType->id,
            'exam_type_category_id' => null,
            'value' => 4,
            'is_active' => true,
        ])->create();

        Condition::factory()->state([
            'name' => 'Exam Max Score',
            'condition_category' => \App\Enums\ConditionCategory::MaxScore->value,
            'exam_type_id' => $examType->id,
            'exam_type_category_id' => null,
            'value' => 500,
            'is_active' => true,
        ])->create();

        $answers = collect();

        for ($i = 0; $i < 10; $i++) {
            $question = Question::factory()->state(['category_id' => $category->id])->create();

            $questionOptions = collect();
            for ($j = 0; $j < 4; $j++) {
                $isCorrect = $j === 0;
                $questionOptions->push(QuestionOption::factory()
                    ->state(['is_correct' => $isCorrect])
                    ->for($question)->create());
            }

            if ($i < 3) {
                $answers->push([
                    'question_id' => $question->id,
                    'answer_id' => $questionOptions->firstWhere('is_correct', true)->id,
                ]);
            } elseif ($i < 6) {
                $answers->push([
                    'question_id' => $question->id,
                    'answer_id' => $questionOptions->firstWhere('is_correct', false)->id,
                ]);
            } else {
                $answers->push([
                    'question_id' => $question->id,
                    'answer_id' => null,
                ]);
            }
        }

        Sanctum::actingAs($user, ['student.exam.answer']);

        Log::info([$answers]);

        $response = $this->postJson($this->apiUrl.$exam->id.'/answer', [
            'answers' => $answers,
        ]);

        $response->assertStatus(201);
    }

    /*
     * Specific subject exam
     */
    public function test_custom_exam_answer()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();

        $category = QuestionCategory::factory()->create();

        $exam = Exam::factory()->state([
            'user_id' => $user->id,
            'exam_type_id' => null,
        ])->create();

        $answers = collect();

        for ($i = 0; $i < 30; $i++) {
            $question = Question::factory()->state(['category_id' => $category->id])->create();

            $questionOptions = collect();
            for ($j = 0; $j < 4; $j++) {
                $isCorrect = $j === 0;
                $questionOptions->push(QuestionOption::factory()
                    ->state(['is_correct' => $isCorrect])
                    ->for($question)->create());
            }

            if ($i < 20) {
                $answers->push([
                    'question_id' => $question->id,
                    'answer_id' => $questionOptions->firstWhere('is_correct', true)->id,
                ]);
            } elseif ($i < 25) {
                $answers->push([
                    'question_id' => $question->id,
                    'answer_id' => $questionOptions->firstWhere('is_correct', false)->id,
                ]);
            } else {
                $answers->push([
                    'question_id' => $question->id,
                    'answer_id' => null,
                ]);
            }
        }

        Sanctum::actingAs($user, ['student.exam.answer']);

        $response = $this->postJson($this->apiUrl.$exam->id.'/answer', [
            'answers' => $answers,
        ]);

        $response->assertStatus(201);
    }

    public function test_exam_delete()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        $exam = Exam::factory()->state(['user_id' => $user->id])->create();

        Sanctum::actingAs($user);

        $response = $this->delete($this->apiUrl.$exam->id);

        $response->assertStatus(200);
    }
}
