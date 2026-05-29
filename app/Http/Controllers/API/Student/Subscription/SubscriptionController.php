<?php

namespace App\Http\Controllers\API\Student\Subscription;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Student\Subscription\StoreSubscriptionRequest;
use App\Http\Requests\Student\Subscription\UpgradeSubscriptionRequest;
use App\Http\Resources\Student\Subscription\SubscriptionResource;
use App\Services\Student\Subscription\SubscriptionService;
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
     * Display a listing of the user's subscriptions.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.subscription.list'),
            Response::HTTP_FORBIDDEN
        );

        $subscriptions = $this->subscriptionService->list(auth()->user());

        return $this->successResponse(SubscriptionResource::collection($subscriptions));
    }

    /**
     * Store a newly created subscription.
     */
    public function store(StoreSubscriptionRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.subscription.create'),
            Response::HTTP_FORBIDDEN
        );

        $subscription = $this->subscriptionService->create($request, auth()->user());

        return $this->successResponse(
            new SubscriptionResource($subscription),
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified subscription.
     */
    public function show(string $subscription): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.subscription.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(
            new SubscriptionResource($this->subscriptionService->show(auth()->user(), $subscription))
        );
    }

    /**
     * Cancel the specified subscription.
     */
    public function cancel(string $subscription): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.subscription.cancel'),
            Response::HTTP_FORBIDDEN
        );

        $result = $this->subscriptionService->cancel(auth()->user(), $subscription);

        return $this->successResponse(new SubscriptionResource($result));
    }

    /**
     * Upgrade the specified subscription to a new plan.
     */
    public function upgrade(UpgradeSubscriptionRequest $request, string $subscription): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.subscription.upgrade'),
            Response::HTTP_FORBIDDEN
        );

        $result = $this->subscriptionService->upgrade($request, auth()->user(), $subscription);

        return $this->successResponse(new SubscriptionResource($result));
    }

    /**
     * Download invoice for the subscription.
     */
    public function invoice(string $subscription): mixed
    {
        abort_unless(auth()->user()->tokenCan('student.subscription.invoice'),
            Response::HTTP_FORBIDDEN
        );

        $user = auth()->user();
        $sub = $this->subscriptionService->show($user, $subscription);

        return $user->downloadInvoice([
            'name' => config('app.name'),
            'street' => '',
            'city' => '',
            'postalCode' => '',
            'country' => '',
            'phone' => $user->phone ?? '',
            'email' => $user->email,
            'website' => config('app.url'),
            'vatId' => '',
        ]);
    }
}
