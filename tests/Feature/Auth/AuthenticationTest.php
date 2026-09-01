<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertSuccessful();
    }

    public function test_users_can_authenticate_using_username(): void
    {
        $user = User::factory()->create([
            'username' => 'customuser',
        ]);

        $response = $this->post('/login', [
            'email' => 'customuser',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertSuccessful();
    }

    public function test_users_can_authenticate_using_username_with_whitespace(): void
    {
        $user = User::factory()->create([
            'username' => 'customuser',
        ]);

        $response = $this->post('/login', [
            'email' => '  customuser  ',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertSuccessful();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_not_authenticate_with_overlong_login_string(): void
    {
        $response = $this->postJson('/login', [
            'email' => str_repeat('a', 256),
            'password' => 'password',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['email']);
    }
}
