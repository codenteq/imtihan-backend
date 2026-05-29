<?php

namespace App\Services\Admin\Subscription;

use Codenteq\Iyzico\Services\PlanService;

class SubscriptionPlanService
{
    private PlanService $planService;

    public function __construct()
    {
        $this->planService = new PlanService();
    }

    /**
     * Display a listing of the plans for a product.
     */
    public function list(string $productReferenceCode, array $params): mixed
    {
        return $this->planService->list($productReferenceCode, $params);
    }

    /**
     * Store a newly created plan.
     */
    public function create(object $request): mixed
    {
        return $this->planService->create(
            $request->validated()['product_reference_code'],
            $request->validated()
        );
    }

    /**
     * Display the specified plan.
     */
    public function show(string $referenceCode): mixed
    {
        return $this->planService->retrieve($referenceCode);
    }

    /**
     * Update the specified plan.
     */
    public function update(object $request, string $referenceCode): mixed
    {
        return $this->planService->update($referenceCode, $request->validated());
    }

    /**
     * Remove the specified plan.
     */
    public function destroy(string $referenceCode): mixed
    {
        return $this->planService->delete($referenceCode);
    }
}
