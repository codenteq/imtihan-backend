<?php

namespace Tests\Feature\Student\StaticPage;

use App\Models\StaticPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StaticPageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/student/static-pages/';

    /*public function test_static_page_list()
    {
        StaticPage::factory(20)->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['student.static-page.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(10);
    }*/

    public function test_static_page_show()
    {
        $staticPage = StaticPage::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['student.static-page.show']);

        $response = $this->get($this->apiUrl.$staticPage->id);
        $response->assertJsonFragment(['id' => $staticPage->id]);
    }
}
