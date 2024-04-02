<?php

namespace App\Http\Requests\Admin\Question;

use App\Enums\Difficulty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'description' => 'string',
            'category_id' => 'numeric|exists:question_categories,id',
            'is_image_option' => 'numeric',
            'src' => 'file',
            'language_id' => 'numeric|exists:languages,id',
            'options' => 'array',
            'difficulty' => [
                Rule::enum(Difficulty::class),
            ],
        ];
    }
}
