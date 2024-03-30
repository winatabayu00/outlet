<?php

namespace App\Services\Brand;

use App\Actions\Brands\CreateBrand;
use App\Actions\Brands\UpdateBrand;
use App\Enums\Media\MediaCollectionNames;
use App\Models\Brands\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Winata\PackageBased\Abstracts\BaseService;

class BrandService extends BaseService
{

    /**
     * @param Request $request
     * @return Brand
     * @throws ValidationException
     * @throws \Throwable
     */
    public function create(
        Request $request
    ): Brand
    {
        $validated = $this->validate(
            inputs: $request->input(),
            rules: [
                'name' => ['required', 'max:255'],
                'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]
        );

        DB::beginTransaction();
        // create new brand
        $newBrand = (new CreateBrand(inputs: $validated))
            ->handle();

        // link brand logo
        linkedMediaCollection(
            model: $newBrand,
            source: $request,
            inputName: 'logo',
        )->setCollectionName(MediaCollectionNames::BRAND_LOGO->value);

        DB::commit();

        return $newBrand->refresh();
    }

    /**
     * @param Brand $brand
     * @param Request $request
     * @return Brand
     * @throws ValidationException
     */
    public function update(
        Brand   $brand,
        Request $request,
    ): Brand
    {
        $validated = $this->validate(
            inputs: $request->input(),
            rules: [
                'name' => ['required', 'max:255'],
                'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]
        );

        DB::beginTransaction();

        // update brand
        $newBrand = (new UpdateBrand(brand: $brand, inputs: $validated))
            ->handle();

        // link brand logo
        linkedMediaCollection(
            model: $newBrand,
            source: $request,
            inputName: 'logo',
        )->setCollectionName(MediaCollectionNames::BRAND_LOGO->value)
            ->deletePreviousMedia(true);

        DB::commit();

        return $newBrand->refresh();
    }
}
