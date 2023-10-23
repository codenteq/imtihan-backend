<?php

namespace App\Http\Requests\Admin\User;

use App\Enums\Role;
use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'string|max:255',
            'phone' => 'string|max:255',
            'is_active' => [new Enum(Status::class)],
            'role' => [new Enum(Role::class)],
        ];
    }
}
