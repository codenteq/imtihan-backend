<?php

namespace App\Http\Controllers\API\Admin\Subscription;

use App\Http\Controllers\API\ApiController;
use App\Http\Resources\Admin\Subscription\SubscriptionResource;
use App\Services\Admin\Subscription\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends ApiController
{
    private SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $service)
    {
        $this->subscriptionService = $service;
    }

    /**
     * Display a listing of subscriptions.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.list'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse($this->subscriptionService->paginate());
    }

    /**
     * Display the specified subscription.
     */
    public function show(string $subscription): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(
            new SubscriptionResource($this->subscriptionService->show($subscription))
        );
    }

    /**
     * Cancel the specified subscription.
     */
    public function cancel(string $subscription): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.cancel'),
            Response::HTTP_FORBIDDEN
        );

        $result = $this->subscriptionService->cancel($subscription);

        return $this->successResponse(new SubscriptionResource($result));
    }
}
