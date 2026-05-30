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
     * Display a listing of all plans.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.plan.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->planService->search($query));
        }

        return $this->successResponse($this->planService->paginate());
    }

    /**
     * Display a listing of the plans for a product.
     */
    public function indexByProduct(int $product): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.plan.list'),
            Response::HTTP_FORBIDDEN
        );

        $productModel = \App\Models\SubscriptionProduct::findOrFail($product);

        $query = request()->query('query');
        if ($query) {
            return $this->successResponse($this->planService->search($query, 10, ['productReferenceCode' => $productModel->referenceCode]));
        }

        return $this->successResponse($this->planService->paginate([], ['productReferenceCode' => $productModel->referenceCode]));
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
    public function show(int $plan): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.plan.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse($this->planService->show($plan));
    }

    /**
     * Update the specified plan.
     */
    public function update(UpdateSubscriptionPlanRequest $request, int $plan): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.plan.update'),
            Response::HTTP_FORBIDDEN
        );

        $updatedPlan = $this->planService->update($request, $plan);

        return $this->successResponse($updatedPlan);
    }

    /**
     * Remove the specified plan.
     */
    public function destroy(int $plan): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.plan.delete'),
            Response::HTTP_FORBIDDEN
        );

        $deletedPlan = $this->planService->destroy($plan);

        return $this->successResponse($deletedPlan);
    }
}
