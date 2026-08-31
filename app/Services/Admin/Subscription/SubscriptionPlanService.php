<?php

namespace App\Services\Admin\Subscription;

use App\Models\SubscriptionPlan;
use App\Services\Base\BaseService;
use Codenteq\Iyzico\Services\PlanService;
use Illuminate\Support\Facades\Log;

class SubscriptionPlanService extends BaseService
{
    private PlanService $planService;

    public function __construct(PlanService $planService)
    {
        parent::__construct(SubscriptionPlan::class);
        $this->planService = $planService;
    }

    /**
     * Store a newly created plan.
     */
    public function create(object $request): object|array
    {
        $validatedData = $request->validated();

        $validatedData['price'] = number_format((float) $validatedData['price'], 2, '.', '');

        if (empty($validatedData['trial_period_days'])) {
            $validatedData['trial_period_days'] = null;
        }

        Log::info('Iyzico Plan Request Payload:', $validatedData);

        $iyzicoPlan = $this->planService->create(
            $validatedData['product_reference_code'],
            $validatedData
        );

        if ($iyzicoPlan->getStatus() !== 'success') {
            Log::error('Iyzico Plan Creation Failed:', ['raw_result' => $iyzicoPlan->getRawResult(), 'status' => $iyzicoPlan->getStatus(), 'error' => $iyzicoPlan->getErrorMessage()]);
            throw new \Exception($iyzicoPlan->getErrorMessage() ?? 'Plan creation failed');
        }

        return $this->model::create([
            'referenceCode' => $iyzicoPlan->getReferenceCode(),
            'productReferenceCode' => $request->validated()['product_reference_code'],
            'name' => $iyzicoPlan->getName(),
            'price' => $iyzicoPlan->getPrice(),
            'currencyCode' => $iyzicoPlan->getCurrencyCode(),
            'paymentInterval' => $iyzicoPlan->getPaymentInterval(),
            'paymentIntervalCount' => $iyzicoPlan->getPaymentIntervalCount(),
            'planPaymentType' => $iyzicoPlan->getPlanPaymentType(),
            'recurrenceCount' => $iyzicoPlan->getRecurrenceCount(),
            'trialPeriodDays' => $iyzicoPlan->getTrialPeriodDays() ?? 0,
            'status' => $iyzicoPlan->getStatus(),
        ]);
    }

    /**
     * Update the specified plan.
     */
    public function update(object $request, int $id, array $where = []): object
    {
        $plan = $this->model::where($where)->findOrFail($id);

        $iyzicoPlan = $this->planService->update($plan->referenceCode, $request->validated());

        $plan->update([
            'name' => $iyzicoPlan->getName() ?? $request->name,
            'trialPeriodDays' => $iyzicoPlan->getTrialPeriodDays() ?? $request->trial_period_days,
            'status' => $iyzicoPlan->getStatus() ?? $request->status,
        ]);

        return $plan;
    }

    /**
     * Remove the specified plan.
     */
    public function destroy(int $id, array $where = []): mixed
    {
        $plan = $this->model::where($where)->findOrFail($id);

        $this->planService->delete($plan->referenceCode);
        $plan->delete();

        return response()->json(['message' => 'Plan deleted successfully']);
    }
}
