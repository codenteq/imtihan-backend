<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\QuestionBug;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuestionBug>
 */
class QuestionBugFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->text,
            'question_id' => Question::factory(),
        ];
    }
}
