<?php

namespace App\Http\Controllers\API\Student\Subscription;

use App\Http\Controllers\API\ApiController;
use App\Models\SubscriptionPlan;
use Illuminate\Http\JsonResponse;

class SubscriptionPlanController extends ApiController
{
    public function index(): JsonResponse
    {
        // Students should only see active plans
        $plans = SubscriptionPlan::where('status', 'success')
            ->with('product')
            ->latest()
            ->get();

        return $this->successResponse($plans);
    }
}
