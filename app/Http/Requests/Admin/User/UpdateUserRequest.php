<?php

namespace App\Http\Requests\Admin\User;

use App\Enums\EducationLevel;
use App\Enums\Gender;
use App\Enums\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'string|max:255',
            'phone' => 'string|max:255',
            'email' => 'email',
            'gender' => [
                Rule::enum(Gender::class),
            ],
            'education_level' => [
                Rule::enum(EducationLevel::class),
            ],
            'birth_date' => 'date',
            'is_active' => 'boolean',
            'role' => [
                Rule::enum(Role::class),
            ],
        ];
    }
}
