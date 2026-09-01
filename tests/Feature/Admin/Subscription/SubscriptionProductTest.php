<?php

namespace Tests\Feature\Admin\Subscription;

use App\Models\User;
use Codenteq\Iyzico\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Iyzipay\Model\Subscription\SubscriptionProduct;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscriptionProductTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/admin/subscription/products/';

    public function test_product_list()
    {
        $admin = User::factory()->create();
        Sanctum::actingAs($admin, ['admin.subscription.product.list']);

        $response = $this->get($this->apiUrl.'?page=1&count=10');

        $response->assertStatus(200);
    }

    public function test_product_create()
    {
        $admin = User::factory()->create();
        Sanctum::actingAs($admin, ['admin.subscription.product.create']);

        $mockResponse = new SubscriptionProduct;
        $mockResponse->setStatus('success');
        $mockResponse->setReferenceCode('mock-ref-code');
        $mockResponse->setName('Test Product');
        $mockResponse->setDescription('Test product description');

        $this->mock(ProductService::class, function ($mock) use ($mockResponse) {
            $mock->shouldReceive('create')->once()->andReturn($mockResponse);
        });

        $response = $this->postJson($this->apiUrl, [
            'name' => 'Test Product',
            'description' => 'Test product description',
        ]);

        $response->assertStatus(201);
    }

    public function test_product_create_validation_error()
    {
        $admin = User::factory()->create();
        Sanctum::actingAs($admin, ['admin.subscription.product.create']);

        $response = $this->postJson($this->apiUrl, []);

        $response->assertStatus(422);
    }

    public function test_product_list_forbidden_without_permission()
    {
        $admin = User::factory()->create();
        Sanctum::actingAs($admin, []);

        $response = $this->get($this->apiUrl);

        $response->assertStatus(403);
    }
}
