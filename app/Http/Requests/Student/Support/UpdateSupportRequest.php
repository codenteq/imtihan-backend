<?php

namespace App\Http\Requests\Student\Support;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateSupportRequest extends FormRequest
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
            'subject' => 'string',
            'message' => 'string',
            'is_active' => [new Enum(Status::class)],
            'user_id' => 'numeric|exists:users,id',
        ];
    }
}
