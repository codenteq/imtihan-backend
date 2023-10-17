<?php

namespace App\Http\Requests\Student\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
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
            'full_name' => 'string|max:255',
            'email' => 'email|max:255',
            'phone' => 'string|max:255',
            'address' => 'string|max:255',
            'avatar' => 'file',
            'country_id' => 'numeric|exists:countries,id',
            'city_id' => 'numeric|exists:cities,id',
            'state_id' => 'numeric|exists:states,id',
            'is_active' => 'boolean',
            'language_id' => 'numeric|exists:languages,id',
            'password' => 'string|min:8',
        ];
    }
}
