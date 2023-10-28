<?php

namespace Tests\Feature\Student\ClassSchedule;

use App\Enums\Role;
use App\Models\ClassSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClassScheduleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/student/class-schedules/';

    public function test_class_schedule_list()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        ClassSchedule::factory(20)->state(['user_id' => $user->id])->state([
            'user_id' => $user->id,
        ])->create();

        Sanctum::actingAs($user, ['student.class-schedule.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(20);
    }

    public function test_class_schedule_create()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        $classSchedule = ClassSchedule::factory()->state(['user_id' => $user->id])->make();

        Sanctum::actingAs($user, ['student.class-schedule.create']);

        $response = $this->postJson($this->apiUrl, $classSchedule->toArray());
        $response->assertStatus(201);
    }

    public function test_class_schedule_show()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        $classSchedule = ClassSchedule::factory()->state(['user_id' => $user->id])->create();

        Sanctum::actingAs($user, ['student.class-schedule.show']);

        $response = $this->get($this->apiUrl.$classSchedule->id);
        $response->assertJsonFragment(['id' => $classSchedule->id]);
    }

    public function test_class_schedule_update()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        $classSchedule = ClassSchedule::factory()->state(['user_id' => $user->id])->create();

        Sanctum::actingAs($user, ['student.class-schedule.update']);

        $response = $this->putJson($this->apiUrl.$classSchedule->id, [
            'name' => 'test',
            'description' => 'test',
        ]);
        $response->assertStatus(200);
    }

    public function test_class_schedule_delete()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        $classSchedule = ClassSchedule::factory()->state(['user_id' => $user->id])->create();

        Sanctum::actingAs($user, ['student.class-schedule.delete']);

        $response = $this->delete($this->apiUrl.$classSchedule->id);
        $response->assertStatus(200);
    }
}
