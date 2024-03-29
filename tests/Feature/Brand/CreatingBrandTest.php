<?php

namespace Feature\Brand;

use App\Services\Brand\BrandService;
use Illuminate\Http\Request;

it('can create new brand without logo', function () {
    $brandData = new Request([
        'name' => 'Apple'
    ]);

    $service = new BrandService();
    $newBrand = $service->create($brandData);

    expect($newBrand->name)
        ->toBe($brandData->input('name'));
});
