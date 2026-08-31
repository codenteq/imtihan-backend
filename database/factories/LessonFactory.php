<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\Lesson;
use App\Models\QuestionCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'content' => $this->faker->text,
            'category_id' => QuestionCategory::factory(),
            'language_id' => Language::factory(),
        ];
    }
}
