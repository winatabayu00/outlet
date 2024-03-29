<?php

namespace Feature\Brand;

use App\Enums\ResponseCode\ResponseCode;
use App\Models\Brands\Brand;
use App\Services\Outlet\OutletService;
use Illuminate\Http\Request;
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

// using endpoint
it('can update outlet from endpoint', function () {

    $response = $this->delete(
        uri: route('outlet.destroy', ['outlet' => $this->outlet->id]),
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
