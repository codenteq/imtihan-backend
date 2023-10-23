<?php

namespace Tests\Feature\Admin\Account;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/accounts/';

    public function test_account_show()
    {
        $account = User::factory()->state(['role' => Role::Admin])->create();

        Sanctum::actingAs($account, ['admin.account.show']);

        $response = $this->get($this->apiUrl.$account->id);
        $response->assertJsonFragment(['id' => $account->id]);
    }

    public function test_account_update()
    {
        $account = User::factory()->state(['role' => Role::Admin])->create();

        Sanctum::actingAs($account, ['admin.account.update']);

        $response = $this->putJson($this->apiUrl.$account->id, [
            'full_name' => 'Test',
        ]);
        $response->assertStatus(200);
    }

    public function test_account_delete()
    {
        $account = User::factory()->state(['role' => Role::Admin])->create();

        Sanctum::actingAs($account, ['admin.account.delete']);

        $response = $this->deleteJson($this->apiUrl.$account->id);
        $response->assertStatus(200);
    }
}
