<?php

namespace App\Services\Admin\Subscription;

use Codenteq\Iyzico\Services\ProductService;

class SubscriptionProductService
{
    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    /**
     * Display a listing of the products.
     */
    public function list(array $params): mixed
    {
        return $this->productService->list($params);
    }

    /**
     * Store a newly created product.
     */
    public function create(object $request): mixed
    {
        return $this->productService->create($request->validated());
    }

    /**
     * Display the specified product.
     */
    public function show(string $referenceCode): mixed
    {
        return $this->productService->retrieve($referenceCode);
    }

    /**
     * Update the specified product.
     */
    public function update(object $request, string $referenceCode): mixed
    {
        return $this->productService->update($referenceCode, $request->validated());
    }

    /**
     * Remove the specified product.
     */
    public function destroy(string $referenceCode): mixed
    {
        return $this->productService->delete($referenceCode);
    }
}
