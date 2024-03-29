<?php

namespace Feature\Brand;

use App\Enums\ResponseCode\ResponseCode;
use App\Models\Brands\Brand;
use App\Services\Brand\BrandService;
use Illuminate\Http\Request;
use Illuminate\Testing\Fluent\AssertableJson;
use Winata\Core\Response\Exception\BaseException;


// using endpoint
it('can delete the brand from endpoint', function () {
    $brandData = [
        'name' => 'Apple'
    ];

    $brand = Brand::query()
        ->firstOrFail();

    $response = $this->delete(
        uri: route('brand.destroy', ['brand' => $brand->id]),
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
