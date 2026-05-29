<?php

namespace App\Http\Requests\Admin\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionPlanRequest extends FormRequest
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
            'product_reference_code' => 'required|string',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency_code' => 'required|string|in:TRY,USD,EUR,GBP,IRR',
            'payment_interval' => 'required|string|in:DAILY,WEEKLY,MONTHLY,YEARLY',
            'plan_payment_type' => 'required|string|in:RECURRING',
            'payment_interval_count' => 'required|integer|min:1',
            'recurrence_count' => 'required|integer|min:1',
            'trial_period_days' => 'nullable|integer|min:0',
        ];
    }
}
