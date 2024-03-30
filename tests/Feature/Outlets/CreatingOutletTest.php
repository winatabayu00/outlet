<?php

namespace Feature\Brand;

use App\Enums\ResponseCode\ResponseCode;
use App\Models\Brands\Brand;
use App\Services\Outlet\OutletService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Winata\Core\Response\Exception\BaseException;

it('can create new outlet without picture', function () {
    /** @var Brand $brand */
    $brand = Brand::query()
        ->firstOrFail();

    $outletData = new Request([
        'brand_id' => $brand->id,
        'name' => fake()->name,
        'address' => fake()->address,
        'longitude' => fake()->longitude,
        'latitude' => fake()->latitude,
    ]);

    $service = new OutletService();
    $newOutlet = $service->create($outletData);

    expect(Arr::only($newOutlet->toArray(), [
        'brand_id',
        'name',
        'address',
        'longitude',
        'latitude',
    ]))
        ->tobe($outletData->input());

    return $newOutlet;
});

it('cant create new outlet because the outlet name already exists', function () {
    /** @var Brand $brand */
    $brand = Brand::query()
        ->firstOrFail();

    $outletData = new Request([
        'brand_id' => $brand->id,
        'name' => fake()->name,
        'address' => fake()->address,
        'longitude' => fake()->longitude,
        'latitude' => fake()->latitude,
    ]);

    $service = new OutletService();
    $newOutlet = $service->create($outletData);

    expect(Arr::only($newOutlet->toArray(), [
        'brand_id',
        'name',
        'address',
        'longitude',
        'latitude',
    ]))
        ->tobe($outletData->input());

    $service->create($outletData);
})->throws(BaseException::class, 'The outlet name has been used');

// using endpoint
it('can create new outlet from endpoint', function () {
    /** @var Brand $brand */
    $brand = Brand::query()
        ->firstOrFail();

    $outletData = [
        'brand_id' => $brand->id,
        'name' => fake()->name,
        'address' => fake()->address,
        'longitude' => fake()->longitude,
        'latitude' => fake()->latitude,
    ];

    $response = $this->postJson(
        uri: route('outlet.create'),
        data: $outletData,
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
