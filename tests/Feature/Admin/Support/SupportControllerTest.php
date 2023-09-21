<?php

namespace Tests\Feature\Admin\Support;

use App\Models\Support;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SupportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/supports/';

    public function test_support_list()
    {
        Support::factory(20)->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.support.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(10, 'data');
    }

    public function test_support_show()
    {
        $support = Support::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.support.show']);

        $response = $this->get($this->apiUrl.$support->id);
        $response->assertJsonFragment(['id' => $support->id]);
    }

    public function test_support_update()
    {
        $support = Support::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.support.update']);

        $response = $this->putJson($this->apiUrl.$support->id, [
            'name' => 'New Title',
            'content' => 'New Content',
        ]);
        $response->assertStatus(200);
    }

    public function test_support_delete()
    {
        $support = Support::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.support.delete']);

        $response = $this->deleteJson($this->apiUrl.$support->id);
        $response->assertStatus(200);
    }
}
