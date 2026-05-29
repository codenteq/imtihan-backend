<?php

namespace App\Services\Admin\Subscription;

use Codenteq\Iyzico\Models\Subscription;

class SubscriptionService
{
    /**
     * Display a listing of subscriptions.
     */
    public function paginate(int $perPage = 10): mixed
    {
        return Subscription::with('owner')->latest()->paginate($perPage);
    }

    /**
     * Display the specified subscription.
     */
    public function show(string $id): mixed
    {
        return Subscription::with('owner')->findOrFail($id);
    }

    /**
     * Cancel the specified subscription.
     */
    public function cancel(string $id): mixed
    {
        $subscription = Subscription::findOrFail($id);

        $subscription->cancel();

        return $subscription->fresh();
    }
}
