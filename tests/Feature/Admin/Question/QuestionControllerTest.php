<?php

namespace Tests\Feature\Admin\Question;

use App\Models\Question;
use App\Models\QuestionBug;
use App\Models\QuestionOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class QuestionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/questions/';

    public function test_question_list()
    {
        $question = Question::factory()->create();
        QuestionOption::factory(4)->for($question)->create();

        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.question.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(1, 'data');
    }

    public function test_question_create()
    {
        $question = Question::factory()->create();
        $options = QuestionOption::factory(4)->for($question)->make();

        $data = [
            ...$question->toArray(),
            'options' => $options->toArray(),
        ];

        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.question.create']);

        $response = $this->postJson($this->apiUrl, $data);
        $response->assertStatus(201);
    }

    public function test_question_show()
    {
        $question = Question::factory()->create();
        QuestionOption::factory(4)->for($question)->create();

        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.question.show']);

        $response = $this->get($this->apiUrl.$question->id);
        $response->assertJsonFragment(['id' => $question->id]);
    }

    public function test_question_bug_list()
    {
        QuestionBug::factory(5)->create();

        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.question.show']);

        $response = $this->get($this->apiUrl.'bugs');

        $response->assertJsonCount(5, 'data');
    }

    public function test_question_bug_resolve()
    {
        $questionBug = QuestionBug::factory()->create();

        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.question.delete']);

        $response = $this->delete($this->apiUrl.'bugs/resolve/'.$questionBug->id);

        $response->assertStatus(200);
    }

    public function test_question_update()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create();
        $options = QuestionOption::factory(4)->for($question)->create();

        $data = [
            ...$question->toArray(),
            'options' => $options->toArray(),
        ];

        Sanctum::actingAs($user, ['admin.question.update']);

        $response = $this->putJson($this->apiUrl.$question->id, $data);

        $response->assertStatus(200);
    }

    public function test_question_delete()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create();
        QuestionOption::factory(4)->for($question)->create();

        Sanctum::actingAs($user, ['admin.question.delete']);

        $response = $this->delete($this->apiUrl.$question->id);
        $response->assertStatus(200);
    }
}
