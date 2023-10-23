<?php

namespace App\Http\Requests\Admin\Account;

use App\Enums\Gender;
use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'phone' => 'string|max:255',
            'address' => 'string|max:255',
            'avatar' => 'file',
            'gender' => [new Enum(Gender::class)],
        ];
    }
}
