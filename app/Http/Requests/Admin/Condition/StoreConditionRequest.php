<?php

namespace App\Http\Requests\Admin\Condition;

use Illuminate\Foundation\Http\FormRequest;

class StoreConditionRequest extends FormRequest
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
            'question_category_id' => 'required|numeric|exists:question_categories,id',
            'condition_category_id' => 'required|numeric|exists:condition_categories,id',
            'value' => 'required|numeric',
            'is_active' => 'required|boolean',
        ];
    }
}
