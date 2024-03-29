<?php

namespace Feature\Brand;

use App\Enums\ResponseCode\ResponseCode;
use App\Models\Brands\Brand;
use App\Services\Brand\BrandService;
use Illuminate\Http\Request;
use Illuminate\Testing\Fluent\AssertableJson;
use Winata\Core\Response\Exception\BaseException;

it('can update new brand without logo', function () {
    $brandData = new Request([
        'name' => 'Apple1',
    ]);

    /** @var Brand $brand */
    $brand = Brand::query()
        ->firstOrFail();

    $service = new BrandService();
    $newBrand = $service->update(brand: $brand, request: $brandData);

    expect($newBrand->name)
        ->toBe($brandData->input('name'));
});

it('cant update brand because the brand name already exists', function () {
    /** @var Brand $brand */
    $brand = Brand::query()
        ->firstOrFail();

    $anotherBrand = Brand::query()
        ->where('id', '!=', $brand->id)
        ->firstOrFail();

    $brandData = new Request([
        'name' => $anotherBrand->name
    ]);

    $service = new BrandService();
    $newBrand = $service->update(brand: $brand, request: $brandData);

    expect($newBrand->name)
        ->toBe($brandData->input('name'));
})->throws(BaseException::class, 'The brand name already exists');

// using endpoint
it('can update brand from endpoint', function () {
    $brandData = [
        'name' => 'Apple'
    ];
    /** @var Brand $brand */
    $brand = Brand::query()
        ->firstOrFail();

    $response = $this->putJson(
        uri: route('brand.update', ['brand' => $brand->id]),
        data: $brandData,
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
