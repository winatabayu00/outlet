<?php

namespace Feature\Brand;

use App\Enums\ResponseCode\ResponseCode;
use App\Services\Brand\BrandService;
use Illuminate\Http\Request;
use Illuminate\Testing\Fluent\AssertableJson;
use Winata\Core\Response\Exception\BaseException;

it('can create new brand without logo', function () {
    $brandData = new Request([
        'name' => 'Apple',
    ]);

    $service = new BrandService();
    $newBrand = $service->create($brandData);

    expect($newBrand->name)
        ->toBe($brandData->input('name'));
});

it('cant create new brand because the brand name already exists', function () {
    $brandData = new Request([
        'name' => 'Apple'
    ]);

    $service = new BrandService();
    $newBrand = $service->create($brandData);

    expect($newBrand->name)
        ->toBe($brandData->input('name'));

    $service->create($brandData);
})->throws(BaseException::class, 'The brand name already exists');

// using endpoint
it('can create new brand from endpoint', function () {
    $brandData = [
        'name' => 'Apple'
    ];

    $response = $this->postJson(
        uri: route('brand.create'),
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
