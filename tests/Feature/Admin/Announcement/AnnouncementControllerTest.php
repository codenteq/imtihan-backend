<?php

namespace Tests\Feature\Admin\Announcement;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AnnouncementControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/announcements/';

    public function test_announcement_list()
    {
        Announcement::factory(20)->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.announcement.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(10, 'data');
    }

    public function test_announcement_create()
    {
        $announcement = Announcement::factory()->make();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.announcement.create']);

        $response = $this->postJson($this->apiUrl, $announcement->toArray());
        $response->assertStatus(201);
    }

    public function test_announcement_show()
    {
        $announcement = Announcement::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.announcement.show']);

        $response = $this->get($this->apiUrl.$announcement->id);
        $response->assertJsonFragment(['id' => $announcement->id]);
    }

    public function test_announcement_update()
    {
        $announcement = Announcement::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.announcement.update']);

        $response = $this->putJson($this->apiUrl.$announcement->id, [
            'name' => 'New Title',
            'content' => 'New Content',
        ]);
        $response->assertStatus(200);
    }

    public function test_announcement_delete()
    {
        $announcement = Announcement::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['admin.announcement.delete']);

        $response = $this->deleteJson($this->apiUrl.$announcement->id);
        $response->assertStatus(200);
    }
}
