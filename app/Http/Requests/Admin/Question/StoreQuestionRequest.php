<?php

namespace App\Http\Requests\Admin\Question;

use App\Enums\Difficulty;
use App\Enums\QuestionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuestionRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|numeric|exists:question_categories,id',
            'is_image_option' => 'boolean',
            'src' => 'file',
            'language_id' => 'required|numeric|exists:languages,id',
            'options' => 'required|array',
            'difficulty' => [
                'required',
                Rule::enum(Difficulty::class)
            ],
            'status' => [
                'required',
                Rule::enum(QuestionStatus::class)
            ],
        ];
    }
}
