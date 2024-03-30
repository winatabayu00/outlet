<?php

namespace Feature\Brand;

use App\Enums\ResponseCode\ResponseCode;
use App\Models\Brands\Brand;
use App\Models\Products\Product;
use App\Services\Outlet\OutletService;
use Illuminate\Http\Request;
use Illuminate\Testing\Fluent\AssertableJson;
use Winata\Core\Response\Exception\BaseException;

beforeEach(function () {
    $this->product = Product::query()
        ->firstOrFail();
});

// using endpoint
it('can update outlet from endpoint', function () {

    $response = $this->delete(
        uri: route('product.destroy', ['product' => $this->product->id]),
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
