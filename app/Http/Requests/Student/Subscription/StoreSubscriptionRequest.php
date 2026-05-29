<?php

namespace App\Http\Requests\Student\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'pricing_plan_reference_code' => 'required|string',
            'status' => 'nullable|string|in:ACTIVE,PENDING',
            'gsm_number' => 'required|string',
            'identity_number' => 'required|string|size:11',
            'billing_city' => 'required|string|max:255',
            'billing_country' => 'required|string|max:255',
            'billing_address' => 'required|string|max:500',
            'billing_zip_code' => 'required|string|max:10',
            'card_holder_name' => 'required|string|max:255',
            'card_number' => 'required|string',
            'expire_month' => 'required|string|size:2',
            'expire_year' => 'required|string|size:4',
            'cvc' => 'required|string|min:3|max:4',
        ];
    }
}
