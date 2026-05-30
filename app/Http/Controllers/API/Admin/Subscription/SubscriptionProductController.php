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

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->productService->search($query));
        }

        return $this->successResponse($this->productService->paginate());
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
    public function show(int $product): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.product.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse($this->productService->show($product));
    }

    /**
     * Update the specified product.
     */
    public function update(UpdateSubscriptionProductRequest $request, int $product): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.product.update'),
            Response::HTTP_FORBIDDEN
        );

        $updatedProduct = $this->productService->update($request, $product);

        return $this->successResponse($updatedProduct);
    }

    /**
     * Remove the specified product.
     */
    public function destroy(int $product): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.subscription.product.delete'),
            Response::HTTP_FORBIDDEN
        );

        $deletedProduct = $this->productService->destroy($product);

        return $this->successResponse($deletedProduct);
    }
}
