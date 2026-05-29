<?php

namespace App\Services\Student\Subscription;

use App\Models\User;
use Codenteq\Iyzico\Enums\UpgradePeriodEnum;
use Codenteq\Iyzico\Models\Subscription;
use Codenteq\Iyzico\Services\SubscriptionService as CashierSubscriptionService;

class SubscriptionService
{
    private CashierSubscriptionService $subscriptionService;

    public function __construct()
    {
        $this->subscriptionService = new CashierSubscriptionService();
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

        $data['customer'] = [
            'name' => $user->full_name,
            'surname' => $user->full_name,
            'gsmNumber' => $data['gsm_number'],
            'email' => $user->email,
            'identityNumber' => $data['identity_number'],
            'billingAddress' => [
                'contactName' => $user->full_name,
                'city' => $data['billing_city'],
                'country' => $data['billing_country'],
                'address' => $data['billing_address'],
                'zipCode' => $data['billing_zip_code'],
            ],
            'shippingAddress' => [
                'contactName' => $user->full_name,
                'city' => $data['billing_city'],
                'country' => $data['billing_country'],
                'address' => $data['billing_address'],
                'zipCode' => $data['billing_zip_code'],
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

        if ($response->getStatus() === 'success') {
            return Subscription::create([
                'user_id' => $user->id,
                'name' => $data['name'] ?? 'default',
                'iyzico_id' => $response->getReferenceCode(),
                'iyzico_plan' => $data['pricing_plan_reference_code'],
                'iyzico_status' => 'ACTIVE',
            ]);
        }

        return $response;
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
