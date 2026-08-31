<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'content' => $this->faker->paragraph,
            'is_everyone' => Status::Inactive,
            'user_id' => User::factory(),
        ];
    }
}
