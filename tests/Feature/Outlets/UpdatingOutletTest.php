<?php

namespace Feature\Brand;

use App\Enums\ResponseCode\ResponseCode;
use App\Models\Brands\Brand;
use App\Services\Outlet\OutletService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Winata\Core\Response\Exception\BaseException;

beforeEach(function () {
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
    $this->outlet = $service->create($outletData);
});

it('can update outlet without picture', function () {

    $outletData = new Request([
        'name' => fake()->name,
        'address' => fake()->address,
        'longitude' => fake()->longitude,
        'latitude' => fake()->latitude,
    ]);

    $service = new OutletService();
    $outlet = $service->update(outlet: $this->outlet, request: $outletData);

    expect(Arr::only($outlet->toArray(), [
        'name',
        'address',
        'longitude',
        'latitude',
    ]))
        ->tobe($outletData->input());

    return $outlet;
});

it('cant update outlet because the outlet name has been used', function () {
    $outletData = new Request([
        'brand_id' => $this->outlet->brand_id,
        'name' => $this->outlet->name,
        'address' => fake()->address,
        'longitude' => fake()->longitude,
        'latitude' => fake()->latitude,
    ]);

    $service = new OutletService();
    $outlet = $service->create(request: $outletData);

    expect(Arr::only($outlet->toArray(), [
        'brand_id',
        'name',
        'address',
        'longitude',
        'latitude',
    ]))
        ->tobe($outletData->input());

    $updatedOutlet = $service->update(outlet: $outlet, request: $outletData);
    expect(Arr::only($updatedOutlet->toArray(), [
        'brand_id',
        'name',
        'address',
        'longitude',
        'latitude',
    ]))
        ->tobe($outletData->input());

})->throws(BaseException::class, 'The outlet name has been used');

// using endpoint
it('can update outlet from endpoint', function () {
    $outletData = [
        'name' => fake()->name,
        'address' => fake()->address,
        'longitude' => fake()->longitude,
        'latitude' => fake()->latitude,
    ];

    $response = $this->putJson(
        uri: route('outlet.update', ['outlet' => $this->outlet->id]),
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
