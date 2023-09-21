<?php

namespace App\Http\Controllers\API\Admin\Payment;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Admin\Payment\StorePaymentCouponRequest;
use App\Http\Requests\Admin\Payment\UpdatePaymentCouponRequest;
use App\Http\Resources\Admin\Payment\PaymentCouponResource;
use App\Services\Admin\Payment\PaymentCouponService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PaymentCouponController extends ApiController
{
    private PaymentCouponService $paymentCouponService;

    public function __construct(PaymentCouponService $service)
    {
        $this->paymentCouponService = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.payment-coupon.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->paymentCouponService->search($query));
        }

        return $this->successResponse($this->paymentCouponService->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentCouponRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.payment-coupon.create'),
            Response::HTTP_FORBIDDEN
        );

        $payment_coupon = $this->paymentCouponService->create($request);

        return $this->successResponse($payment_coupon, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $payment_coupon): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.payment-coupon.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new PaymentCouponResource($this->paymentCouponService->show($payment_coupon)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentCouponRequest $request, int $payment_coupon): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.payment-coupon.update'),
            Response::HTTP_FORBIDDEN
        );

        $payment_coupon = $this->paymentCouponService->update($request, $payment_coupon);

        return $this->successResponse($payment_coupon);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $payment_coupon): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.payment-coupon.delete'),
            Response::HTTP_FORBIDDEN
        );

        $payment_coupon = $this->paymentCouponService->destroy($payment_coupon);

        return $this->successResponse($payment_coupon);
    }
}
