<?php

namespace App\Http\Requests\Student\ClassSchedule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassScheduleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string',
            'description' => 'string',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'user_id' => 'numeric|exists:users,id',
        ];
    }
}
