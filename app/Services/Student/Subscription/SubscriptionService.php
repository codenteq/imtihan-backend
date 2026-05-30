<?php

namespace App\Services\Student\Subscription;

use App\Models\User;
use Codenteq\Iyzico\Enums\UpgradePeriodEnum;
use Codenteq\Iyzico\Models\Subscription;
use Codenteq\Iyzico\Services\SubscriptionService as CashierSubscriptionService;

class SubscriptionService
{
    private CashierSubscriptionService $subscriptionService;

    public function __construct(CashierSubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Display a listing of the user's subscriptions.
     */
    public function list(User $user): mixed
    {
        return $user->subscriptions()->latest()->get();
    }

    /**
     * Store a newly created subscription.
     */
    public function create(object $request, User $user): mixed
    {
        $data = $request->validated();

        $cityName = $user->city_id ? \App\Models\City::find($user->city_id)?->name ?? 'Istanbul' : 'Istanbul';
        $countryName = $user->country_id ? \App\Models\Country::find($user->country_id)?->name ?? 'Türkiye' : 'Türkiye';
        $address = $user->address ?? 'Imtihan App Default Address';

        $data['customer'] = [
            'name' => $user->full_name,
            'surname' => $user->full_name,
            'gsmNumber' => $user->phone,
            'email' => $user->email,
            'identityNumber' => '11111111111',
            'billingAddress' => [
                'contactName' => $user->full_name,
                'city' => $cityName,
                'country' => $countryName,
                'address' => $address,
                'zipCode' => '34000',
            ],
            'shippingAddress' => [
                'contactName' => $user->full_name,
                'city' => $cityName,
                'country' => $countryName,
                'address' => $address,
                'zipCode' => '34000',
            ],
        ];

        $data['card'] = [
            'cardHolderName' => $data['card_holder_name'],
            'cardNumber' => $data['card_number'],
            'expireMonth' => $data['expire_month'],
            'expireYear' => $data['expire_year'],
            'cvc' => $data['cvc'],
        ];

        $response = $this->subscriptionService->create([
            'pricing_plan_reference_code' => $data['pricing_plan_reference_code'],
            'status' => $data['status'] ?? 'ACTIVE',
            'customer' => $data['customer'],
            'card' => $data['card'],
        ]);

        if ($response->getStatus() !== 'success') {
            throw new \Exception($response->getErrorMessage() ?? 'Subscription creation failed');
        }

        $plan = \App\Models\SubscriptionPlan::where('referenceCode', $data['pricing_plan_reference_code'])->first();
        $totalPrice = (float) ($plan ? $plan->price : '0');
        $taxRate = 20;
        $basePrice = $totalPrice / (1 + ($taxRate / 100));
        $taxPrice = $totalPrice - $basePrice;

        $trialDays = (int) ($plan ? $plan->trialPeriodDays : 0);
        $trialEndsAt = $trialDays > 0 ? now()->addDays($trialDays) : null;

        $cardNumber = str_replace(' ', '', $data['card_number']);
        $pmLastFour = substr($cardNumber, -4);

        $user->iyzico_id = $response->getReferenceCode();
        $user->pm_last_four = $pmLastFour;
        $user->trial_ends_at = $trialEndsAt;
        $user->save();

        return Subscription::create([
            'user_id' => $user->id,
            'name' => $data['name'] ?? ($plan ? $plan->name : 'default'),
            'iyzico_id' => $response->getReferenceCode(),
            'iyzico_plan' => $data['pricing_plan_reference_code'],
            'iyzico_price' => (string) round($totalPrice, 2),
            'base_price' => (string) round($basePrice, 2),
            'tax_price' => (string) round($taxPrice, 2),
            'tax_rate' => (string) $taxRate,
            'trial_ends_at' => $trialEndsAt,
            'iyzico_status' => $trialEndsAt ? 'TRIAL' : 'ACTIVE',
        ]);
    }

    /**
     * Display the specified subscription.
     */
    public function show(User $user, string $id): mixed
    {
        return $user->subscriptions()->findOrFail($id);
    }

    /**
     * Cancel the specified subscription.
     */
    public function cancel(User $user, string $id): mixed
    {
        $subscription = $user->subscriptions()->findOrFail($id);

        $subscription->cancel();

        return $subscription->fresh();
    }

    /**
     * Upgrade the specified subscription to a new plan.
     */
    public function upgrade(object $request, User $user, string $id): mixed
    {
        $data = $request->validated();
        $subscription = $user->subscriptions()->findOrFail($id);

        $subscription->upgrade(
            $data['reset_recurrence_count'] ?? false,
            $data['use_trial'] ?? false,
            $data['new_pricing_plan_reference_code'],
            UpgradePeriodEnum::from($data['upgrade_period'] ?? 'NOW')
        );

        return $subscription->fresh();
    }
}
