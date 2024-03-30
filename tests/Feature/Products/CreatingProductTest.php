<?php

namespace Feature\Brand;

use App\Enums\ResponseCode\ResponseCode;
use App\Models\Brands\Brand;
use App\Models\Outlets\Outlet;
use App\Services\Outlet\OutletService;
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
});

it('can create new product without outlet and picture', function () {
    $productData = new Request([
        'brand_id' => $this->brand->id,
        'outlet_id' => null,
        'name' => 'product 11',
        'price' => $this->getRandomPrice,
    ]);

    $service = new ProductService();
    $newOutlet = $service->create($productData);

    expect(Arr::only($newOutlet->toArray(), [
        'brand_id',
        'outlet_id',
        'name',
        'price',
    ]))
        ->tobe($productData->input());

    return $newOutlet;
});

it('can create new product without picture', function () {

    /** @var Outlet $outlet */
    $outlet = Outlet::query()
        ->where('brand_id', '=', $this->brand->id)
        ->firstOrFail();

    $productData = new Request([
        'brand_id' => $this->brand->id,
        'outlet_id' => $outlet->id,
        'name' => 'product 11',
        'price' => $this->getRandomPrice,
    ]);

    $service = new ProductService();
    $newOutlet = $service->create($productData);

    expect(Arr::only($newOutlet->toArray(), [
        'brand_id',
        'outlet_id',
        'name',
        'price',
    ]))
        ->tobe($productData->input());

    return $newOutlet;
});

it('cant create new product because outlet brand_id not same with brand_id', function () {
    /** @var Outlet $outlet */
    $outlet = Outlet::query()
        ->where('brand_id', '!=', $this->brand->id)
        ->firstOrFail();

    $productData = new Request([
        'brand_id' => $this->brand->id,
        'outlet_id' => $outlet->id,
        'name' => 'product 11',
        'price' => $this->getRandomPrice,
    ]);

    $service = new ProductService();
    $service->create($productData);
})->throws(BaseException::class, 'We cannot found the outlet');

// using endpoint
it('can create new product from endpoint', function () {
    /** @var Outlet $outlet */
    $outlet = Outlet::query()
        ->where('brand_id', '=', $this->brand->id)
        ->firstOrFail();

    $productData = [
        'brand_id' => $this->brand->id,
        'outlet_id' => $outlet->id,
        'name' => 'product 11',
        'price' => $this->getRandomPrice,
    ];

    $response = $this->postJson(
        uri: route('product.create'),
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
