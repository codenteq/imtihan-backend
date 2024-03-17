<?php

namespace Database\Factories;

use App\Models\ExamType;
use App\Models\QuestionCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamTypeCategory>
 */
class ExamTypeCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exam_type_id' => ExamType::factory(),
            'question_category_id' => QuestionCategory::factory(),
        ];
    }
}
