<?php

namespace Tests\Feature\Student\Account;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/student/accounts/';

    public function test_account_show()
    {
        $account = User::factory()->state(['role' => Role::Student])->create();

        Sanctum::actingAs($account, ['student.account.show']);

        $response = $this->get($this->apiUrl);
        $response->assertJsonFragment(['id' => $account->id]);
    }

    public function test_account_update()
    {
        $account = User::factory()->state(['role' => Role::Student])->create();

        Sanctum::actingAs($account, ['student.account.update']);

        $response = $this->putJson($this->apiUrl, [
            'full_name' => 'Test',
        ]);
        $response->assertStatus(200);
    }

    public function test_account_delete()
    {
        $account = User::factory()->state(['role' => Role::Student])->create();

        Sanctum::actingAs($account, ['student.account.delete']);

        $response = $this->deleteJson($this->apiUrl);
        $response->assertStatus(200);
    }
}
