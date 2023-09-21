<?php

namespace App\Http\Requests\Admin\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
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
            'content' => 'string',
            'category_id' => 'numeric|exists:question_categories,id',
            'language_id' => 'numeric|exists:languages,id',
        ];
    }
}
