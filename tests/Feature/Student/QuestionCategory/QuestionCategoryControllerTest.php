<?php

namespace Tests\Feature\Student\QuestionCategory;

use App\Enums\Role;
use App\Models\Note;
use App\Models\QuestionCategory;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class QuestionCategoryControllerTest extends TestCase
{
    protected string $apiUrl = '/api/student/question-categories/';

    public function test_question_category_list(): void
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        QuestionCategory::factory(10)
            ->state(['parent_id' => null])
            ->create();

        Sanctum::actingAs($user, ['student.question-category.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(10);
    }
}
