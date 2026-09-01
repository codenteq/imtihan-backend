<?php

namespace App\Services\Admin\Subscription;

use App\Models\SubscriptionProduct;
use App\Services\Base\BaseService;
use Codenteq\Iyzico\Services\ProductService;

class SubscriptionProductService extends BaseService
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        parent::__construct(SubscriptionProduct::class);
        $this->productService = $productService;
    }

    /**
     * Store a newly created product.
     */
    public function create(object $request): object|array
    {
        $iyzicoProduct = $this->productService->create($request->validated());

        if ($iyzicoProduct->getStatus() !== 'success') {
            throw new \Exception($iyzicoProduct->getErrorMessage() ?? 'Product creation failed');
        }

        return $this->model::create([
            'referenceCode' => $iyzicoProduct->getReferenceCode(),
            'name' => $iyzicoProduct->getName(),
            'description' => $iyzicoProduct->getDescription(),
            'status' => $iyzicoProduct->getStatus(),
        ]);
    }

    /**
     * Update the specified product.
     */
    public function update(object $request, int $id, array $where = []): object
    {
        $product = $this->model::where($where)->findOrFail($id);

        $iyzicoProduct = $this->productService->update($product->referenceCode, $request->validated());

        $product->update([
            'name' => $iyzicoProduct->getName() ?? $request->name,
            'description' => $iyzicoProduct->getDescription() ?? $request->description,
        ]);

        return $product;
    }

    /**
     * Remove the specified product.
     */
    public function destroy(int $id, array $where = []): mixed
    {
        $product = $this->model::where($where)->findOrFail($id);

        $this->productService->delete($product->referenceCode);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
