<?php

namespace Tests\Feature\Student\Account;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/student/accounts/';

    public function test_account_list()
    {
        $account = User::factory()->state(['role' => User::Student])->create();

        Sanctum::actingAs($account, ['student.account.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(1, 'data');
    }

    public function test_account_show()
    {
        $account = User::factory()->state(['role' => User::Student])->create();

        Sanctum::actingAs($account, ['student.account.show']);

        $response = $this->get($this->apiUrl.$account->id);
        $response->assertJsonFragment(['id' => $account->id]);
    }

    public function test_account_update()
    {
        $account = User::factory()->state(['role' => User::Student])->create();

        Sanctum::actingAs($account, ['student.account.update']);

        $response = $this->putJson($this->apiUrl.$account->id, [
            'full_name' => 'Test',
        ]);
        $response->assertStatus(200);
    }

    public function test_account_delete()
    {
        $account = User::factory()->state(['role' => User::Student])->create();

        Sanctum::actingAs($account, ['student.account.delete']);

        $response = $this->deleteJson($this->apiUrl.$account->id);
        $response->assertStatus(200);
    }
}
