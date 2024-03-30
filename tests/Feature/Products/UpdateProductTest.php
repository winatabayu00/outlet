<?php

namespace Feature\Brand;

use App\Enums\ResponseCode\ResponseCode;
use App\Models\Brands\Brand;
use App\Models\Outlets\Outlet;
use App\Models\Products\Product;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Winata\Core\Response\Exception\BaseException;

beforeEach(function () {
    $listPrice = range(10000, 100000, 10000);
    $this->getRandomPrice = (float)rand(1, count($listPrice) - 1);

    $this->brand = Brand::query()
        ->firstOrFail();

    $this->product = Product::query()
        ->where('brand_id', '=', $this->brand->id)
        ->firstOrFail();
});

it('can update without outlet and picture', function () {
    $productData = new Request([
        'outlet_id' => null,
        'name' => 'product 11',
        'price' => $this->getRandomPrice,
    ]);

    $service = new ProductService();
    $newOutlet = $service->update(product: $this->product, request: $productData);

    expect(Arr::only($newOutlet->toArray(), [
        'outlet_id',
        'name',
        'price',
    ]))
        ->tobe($productData->input());

    return $newOutlet;
});

it('can update without picture', function () {

    /** @var Outlet $outlet */
    $outlet = Outlet::query()
        ->where('brand_id', '=', $this->brand->id)
        ->firstOrFail();

    $productData = new Request([
        'outlet_id' => $outlet->id,
        'name' => 'product 11',
        'price' => $this->getRandomPrice,
    ]);

    $service = new ProductService();
    $newOutlet = $service->update(product: $this->product, request: $productData);

    expect(Arr::only($newOutlet->toArray(), [
        'outlet_id',
        'name',
        'price',
    ]))
        ->tobe($productData->input());

    return $newOutlet;
});

it('cant update because outlet brand_id not same with brand_id', function () {
    /** @var Outlet $outlet */
    $outlet = Outlet::query()
        ->where('brand_id', '!=', $this->product->brand_id)
        ->firstOrFail();

    $productData = new Request([
        'outlet_id' => $outlet->id,
        'name' => 'product 11',
        'price' => $this->getRandomPrice,
    ]);

    $service = new ProductService();
    $service->update(product: $this->product, request: $productData);
})->throws(BaseException::class, 'We cannot found the outlet');

// using endpoint
it('can update from endpoint', function () {
    /** @var Outlet $outlet */
    $outlet = Outlet::query()
        ->where('brand_id', '=', $this->brand->id)
        ->firstOrFail();

    $productData = [
        'outlet_id' => $outlet->id,
        'name' => 'product 11',
        'price' => $this->getRandomPrice,
    ];

    $response = $this->putJson(
        uri: route('product.update', ['product' => $this->product->id]),
        data: $productData,
        headers: [
            'content_type' => 'application/json'
        ]
    )->assertJson(function (AssertableJson $json) {
        assertSuccessResponseFormat($json);
    });

    $rc = $response->json('rc');
    expect(ResponseCode::tryFrom($rc))
        ->toBe(ResponseCode::SUCCESS);
});
