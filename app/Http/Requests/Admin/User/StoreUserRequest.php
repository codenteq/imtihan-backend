<?php

namespace App\Http\Requests\Admin\User;

use App\Enums\EducationLevel;
use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:255',
            'gender' => [
                Rule::enum(Gender::class),
            ],
            'education_level' => [
                'required',
                Rule::enum(EducationLevel::class)
            ],
            'birth_date' => 'date',
            'is_active' => 'required|boolean',
            'role' => 'required|numeric|in:1,2',
        ];
    }
}
