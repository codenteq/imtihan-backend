<?php

namespace Tests\Feature\Student\Announcement;

use App\Enums\Role;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AnnouncementControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/student/announcements/';

    public function test_announcement_list()
    {
        Announcement::factory(20)->create();
        $user = User::factory()->state(['role' => Role::Student])->create();

        Sanctum::actingAs($user, ['student.announcement.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(10, 'data');
    }

    public function test_announcement_show()
    {
        $announcement = Announcement::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['student.announcement.show']);

        $response = $this->get($this->apiUrl.$announcement->id);
        $response->assertJsonFragment(['id' => $announcement->id]);
    }
}
