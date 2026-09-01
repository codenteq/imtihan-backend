<?php

namespace Database\Factories;

use App\Enums\ConditionCategory;
use App\Enums\Status;
use App\Models\Condition;
use App\Models\ExamType;
use App\Models\ExamTypeCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Condition>
 */
class ConditionFactory extends Factory
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
            'exam_type_id' => ExamType::factory(),
            'exam_type_category_id' => ExamTypeCategory::factory(),
            'condition_category' => ConditionCategory::Time,
            'value' => $this->faker->randomDigit(),
            'is_active' => Status::Active,
        ];
    }
}
