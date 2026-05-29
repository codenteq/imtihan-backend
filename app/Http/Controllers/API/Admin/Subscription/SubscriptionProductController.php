<?php

namespace App\Http\Controllers\API\Admin\Subscription;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Admin\Subscription\StoreSubscriptionProductRequest;
use App\Http\Requests\Admin\Subscription\UpdateSubscriptionProductRequest;
use App\Services\Admin\Subscription\SubscriptionProductService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionProductController extends ApiController
{
    private SubscriptionProductService $productService;

    public function __construct(SubscriptionProductService $service)
    {
        $this->productService = $service;
    }

    /**
     * Display a listing of the products.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.product.list'),
            Response::HTTP_FORBIDDEN
        );

        $params = [
            'page' => request()->query('page', 1),
            'count' => request()->query('count', 10),
        ];

        return $this->successResponse($this->productService->list($params));
    }

    /**
     * Store a newly created product.
     */
    public function store(StoreSubscriptionProductRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.product.create'),
            Response::HTTP_FORBIDDEN
        );

        $product = $this->productService->create($request);

        return $this->successResponse($product, Response::HTTP_CREATED);
    }

    /**
     * Display the specified product.
     */
    public function show(string $referenceCode): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.product.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse($this->productService->show($referenceCode));
    }

    /**
     * Update the specified product.
     */
    public function update(UpdateSubscriptionProductRequest $request, string $referenceCode): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.product.update'),
            Response::HTTP_FORBIDDEN
        );

        $product = $this->productService->update($request, $referenceCode);

        return $this->successResponse($product);
    }

    /**
     * Remove the specified product.
     */
    public function destroy(string $referenceCode): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.product.delete'),
            Response::HTTP_FORBIDDEN
        );

        $product = $this->productService->destroy($referenceCode);

        return $this->successResponse($product);
    }
}
