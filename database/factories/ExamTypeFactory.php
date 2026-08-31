<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends Factory<Model>
 */
class ExamTypeFactory extends Factory
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
            'src' => UploadedFile::fake()->image('exam-type.png'),
            'language_id' => Language::factory(),
        ];
    }
}
