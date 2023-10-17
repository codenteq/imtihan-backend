<?php

namespace Tests\Feature\Student\Location;

use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LocationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_country_list()
    {
        $user = User::factory()->create();
        Country::factory(20)->create();

        Sanctum::actingAs($user, ['student.country.list']);

        $response = $this->get('/api/student/countries/');

        $response->assertJsonCount(23);
    }
}
