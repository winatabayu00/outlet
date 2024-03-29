<?php

namespace Feature\Brand;

use App\Services\Brand\BrandService;
use Illuminate\Http\Request;
use Winata\Core\Response\Exception\BaseException;

it('can create new brand without logo', function () {
    $brandData = new Request([
        'name' => 'Apple'
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
