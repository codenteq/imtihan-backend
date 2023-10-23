<?php

namespace App\Http\Requests\Admin\Condition;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateConditionRequest extends FormRequest
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
            'question_category_id' => 'numeric|exists:question_categories,id',
            'condition_category_id' => 'numeric|exists:condition_categories,id',
            'value' => 'numeric',
            'is_active' => [new Enum(Status::class)],
        ];
    }
}
