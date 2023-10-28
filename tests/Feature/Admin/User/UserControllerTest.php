<?php

namespace Tests\Feature\Admin\User;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/users/';

    public function test_user_list()
    {
        $user = User::factory()->state(['role' => Role::Admin])->create();

        Sanctum::actingAs($user, ['admin.user.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(1, 'data');
    }

    public function test_user_create()
    {
        $user = User::factory()->state(['role' => Role::Admin])->make();

        Sanctum::actingAs($user, ['admin.user.create']);

        $response = $this->postJson($this->apiUrl, ['password' => 'admin123', ...$user->toArray()]);
        $response->assertStatus(201);
    }

    public function test_user_show()
    {
        $user = User::factory()->state(['role' => Role::Admin])->create();

        Sanctum::actingAs($user, ['admin.user.show']);

        $response = $this->get($this->apiUrl.$user->id);
        $response->assertJsonFragment(['id' => $user->id]);
    }

    public function test_user_update()
    {
        $user = User::factory()->state(['role' => Role::Admin])->create();

        Sanctum::actingAs($user, ['admin.user.update']);

        $response = $this->putJson($this->apiUrl.$user->id, [
            'full_name' => 'Test',
        ]);
        $response->assertStatus(200);
    }

    public function test_user_delete()
    {
        $user = User::factory()->state(['role' => Role::Admin])->create();

        Sanctum::actingAs($user, ['admin.user.delete']);

        $response = $this->deleteJson($this->apiUrl.$user->id);
        $response->assertStatus(200);
    }
}
