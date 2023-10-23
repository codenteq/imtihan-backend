<?php

namespace Tests\Feature\Student\Support;

use App\Enums\Role;
use App\Models\Support;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SupportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/student/supports/';

    public function test_support_list()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        Support::factory(20)->state(['user_id' => $user->id])->create();

        Sanctum::actingAs($user, ['student.support.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(10, 'data');
    }

    public function test_support_create()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        $support = Support::factory()->make();

        Sanctum::actingAs($user, ['student.support.create']);

        $response = $this->postJson($this->apiUrl, $support->toArray());
        $response->assertStatus(201);
    }

    public function test_support_delete()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        $booking = Support::factory()->state(['user_id' => $user->id])->create();

        Sanctum::actingAs($user, ['student.support.delete']);

        $response = $this->delete($this->apiUrl.$booking->id);
        $response->assertStatus(200);
    }
}
