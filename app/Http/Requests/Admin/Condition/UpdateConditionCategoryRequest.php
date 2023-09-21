<?php

namespace App\Http\Requests\Admin\Condition;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConditionCategoryRequest extends FormRequest
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
            'name' => 'string',
            'key' => 'string',
            'value' => 'numeric',
            'language_id' => 'numeric|exists:languages,id',
        ];
    }
}
