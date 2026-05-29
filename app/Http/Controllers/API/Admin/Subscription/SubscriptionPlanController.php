<?php

namespace App\Http\Controllers\API\Admin\Subscription;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Admin\Subscription\StoreSubscriptionPlanRequest;
use App\Http\Requests\Admin\Subscription\UpdateSubscriptionPlanRequest;
use App\Services\Admin\Subscription\SubscriptionPlanService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionPlanController extends ApiController
{
    private SubscriptionPlanService $planService;

    public function __construct(SubscriptionPlanService $service)
    {
        $this->planService = $service;
    }

    /**
     * Display a listing of the plans for a product.
     */
    public function index(string $productReferenceCode): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.plan.list'),
            Response::HTTP_FORBIDDEN
        );

        $params = [
            'page' => request()->query('page', 1),
            'count' => request()->query('count', 10),
        ];

        return $this->successResponse($this->planService->list($productReferenceCode, $params));
    }

    /**
     * Store a newly created plan.
     */
    public function store(StoreSubscriptionPlanRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.plan.create'),
            Response::HTTP_FORBIDDEN
        );

        $plan = $this->planService->create($request);

        return $this->successResponse($plan, Response::HTTP_CREATED);
    }

    /**
     * Display the specified plan.
     */
    public function show(string $referenceCode): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.plan.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse($this->planService->show($referenceCode));
    }

    /**
     * Update the specified plan.
     */
    public function update(UpdateSubscriptionPlanRequest $request, string $referenceCode): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.plan.update'),
            Response::HTTP_FORBIDDEN
        );

        $plan = $this->planService->update($request, $referenceCode);

        return $this->successResponse($plan);
    }

    /**
     * Remove the specified plan.
     */
    public function destroy(string $referenceCode): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.plan.delete'),
            Response::HTTP_FORBIDDEN
        );

        $plan = $this->planService->destroy($referenceCode);

        return $this->successResponse($plan);
    }
}
