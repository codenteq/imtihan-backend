<?php

namespace App\Http\Requests\Admin\Condition;

use Illuminate\Foundation\Http\FormRequest;

class StoreConditionCategoryRequest extends FormRequest
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
            'name' => 'required|string',
            'key' => 'required|string',
            'value' => 'required|numeric',
            'language_id' => 'required|numeric|exists:languages,id',
        ];
    }
}
