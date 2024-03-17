<?php

namespace Database\Factories;

use App\Enums\Difficulty;
use App\Enums\QuestionStatus;
use App\Enums\Status;
use App\Models\Language;
use App\Models\QuestionCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
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
            'description' => $this->faker->name,
            'category_id' => QuestionCategory::factory(),
            'status' => QuestionStatus::Published->value,
            'is_image_option' => Status::Inactive,
            'src' => UploadedFile::fake()->image('question.png'),
            'language_id' => Language::factory(),
            'difficulty' => Difficulty::Easy,
        ];
    }
}
