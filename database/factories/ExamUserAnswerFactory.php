<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\ExamUserAnswer;
use App\Models\QuestionCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamUserAnswer>
 */
class ExamUserAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exam_id' => Exam::factory(),
            'question_id' => QuestionCategory::factory(),
            'user_id' => User::factory(),
            'answer_id' => ExamUserAnswer::factory(),
        ];
    }
}
