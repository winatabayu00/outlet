<?php

namespace App\Services\Brand;

use App\Actions\Brands\CreateBrand;
use App\Actions\Brands\UpdateBrand;
use App\Concerns\Medias\MediaConcern;
use App\Enums\Media\MediaCollectionNames;
use App\Models\Brands\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Winata\PackageBased\Abstracts\BaseService;

class BrandService extends BaseService
{
    use MediaConcern;

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
                'logo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]
        );

        // create new brand
        $newBrand = (new CreateBrand(inputs: $validated))
            ->handle();

        // link brand logo
        $this->linkedMediaCollection(
            model: $newBrand,
            request: $request,
            inputName: 'logo',
            collectionName: MediaCollectionNames::BRAND_LOGO->value,
        );

        return $newBrand->refresh();
    }

    /**
     * @param Request $request
     * @return Brand
     * @throws ValidationException
     * @throws \Throwable
     */
    public function update(
        Brand $brand,
        Request $request,
    ): Brand
    {
        $validated = $this->validate(
            inputs: $request->input(),
            rules: [
                'name' => ['required', 'max:255'],
                'logo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]
        );

        // update brand
        $newBrand = (new UpdateBrand(brand: $brand, inputs: $validated))
            ->handle();

        // link new brand logo
        $this->linkedMediaCollection(
            model: $newBrand,
            request: $request,
            inputName: 'logo',
            collectionName: MediaCollectionNames::BRAND_LOGO->value,
            deletePreviousMedia: true
        );

        return $newBrand->refresh();
    }
}
