<?php

namespace Tests\Feature\Admin\Lesson;

use App\Models\Lesson;
use App\Models\QuestionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LessonControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/lessons/';

    public function test_lesson_list()
    {
        Lesson::factory(20)->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.lesson.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(10, 'data');
    }

    public function test_lesson_create()
    {
        $category = QuestionCategory::factory()->create();
        $lesson = Lesson::factory($category)->make();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.lesson.create']);

        $response = $this->postJson($this->apiUrl, $lesson->toArray());
        $response->assertStatus(201);
    }

    public function test_lesson_show()
    {
        $lesson = Lesson::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.lesson.show']);

        $response = $this->get($this->apiUrl.$lesson->id);
        $response->assertJsonFragment(['id' => $lesson->id]);
    }

    public function test_lesson_update()
    {
        $lesson = Lesson::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.lesson.update']);

        $response = $this->putJson($this->apiUrl.$lesson->id, [
            'name' => 'updated name',
            'content' => 'updated content',
        ]);

        $response->assertStatus(200);
    }

    public function test_lesson_delete()
    {
        $lesson = Lesson::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.lesson.delete']);

        $response = $this->delete($this->apiUrl.$lesson->id);

        $response->assertStatus(200);
    }
}
