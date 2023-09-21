<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\QuestionCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamResultCategory>
 */
class ExamResultCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'total_questions' => $this->faker->randomNumber,
            'correct' => $this->faker->randomNumber,
            'in_correct' => $this->faker->randomNumber,
            'blank' => $this->faker->randomNumber,
            'category_id' => QuestionCategory::factory(),
            'exam_id' => Exam::factory(),
            'user_id' => User::factory(),
        ];
    }
}
