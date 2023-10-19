<?php

namespace Tests\Feature\Admin\StaticPage;

use App\Models\StaticPage;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StaticPageControllerTest extends TestCase
{
    protected string $apiUrl = '/api/admin/static-pages/';

    public function test_static_page_list()
    {
        StaticPage::factory(20)->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.static-page.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(10, 'data');
    }

    public function test_static_page_create()
    {
        $staticPage = StaticPage::factory()->make();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.static-page.create']);

        $response = $this->postJson($this->apiUrl, $staticPage->toArray());
        $response->assertStatus(201);
    }

    public function test_static_page_show()
    {
        $staticPage = StaticPage::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.static-page.show']);

        $response = $this->get($this->apiUrl.$staticPage->id);
        $response->assertJsonFragment(['id' => $staticPage->id]);
    }

    public function test_static_page_update()
    {
        $staticPage = StaticPage::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.static-page.update']);

        $response = $this->putJson($this->apiUrl.$staticPage->id, [
            'name' => 'updated name',
            'content' => 'updated content',
        ]);

        $response->assertStatus(200);
    }

    public function test_static_page_delete()
    {
        $staticPage = StaticPage::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.static-page.delete']);

        $response = $this->delete($this->apiUrl.$staticPage->id);

        $response->assertStatus(200);
    }
}
