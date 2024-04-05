<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\Role;
use App\Enums\Status;
use App\Models\City;
use App\Models\Country;
use App\Models\Language;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'country_id' => Country::factory(),
            'city_id' => City::factory(),
            'state_id' => State::factory(),
            'is_active' => Status::Active,
            'language_id' => Language::factory(),
            'avatar' => $this->faker->imageUrl,
            'gender' => Gender::Male->value,
            'birth_date' => now()->format('d-m-Y'),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'role' => Role::Admin,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
