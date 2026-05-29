<?php

namespace App\Http\Requests\Student\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class UpgradeSubscriptionRequest extends FormRequest
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
            'new_pricing_plan_reference_code' => 'required|string',
            'reset_recurrence_count' => 'nullable|boolean',
            'use_trial' => 'nullable|boolean',
            'upgrade_period' => 'nullable|string|in:NOW,NEXT_PERIOD',
        ];
    }
}
