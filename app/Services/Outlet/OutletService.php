<?php

namespace App\Services\Outlet;

use App\Actions\Outlets\CreateOutlet;
use App\Actions\Outlets\UpdateOutlet;
use App\Enums\Media\MediaCollectionNames;
use App\Models\Brands\Brand;
use App\Models\Outlets\Outlet;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Winata\PackageBased\Abstracts\BaseService;

class OutletService extends BaseService
{

    /**
     * @param Request $request
     * @return Brand
     * @throws ValidationException
     * @throws \Throwable
     */
    public function create(
        Request $request
    ): Outlet
    {
        $validated = $this->validate(
            inputs: $request->input(),
            rules: [
                'brand_id' => ['required', 'string'],
                'name' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string'],
                'longitude' => ['required', 'numeric'],
                'latitude' => ['required', 'numeric'],
                'picture' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]
        );

        // create new brand
        $newBrand = (new CreateOutlet(inputs: $validated))
            ->handle();

        // link brand picture
        linkedMediaCollection(
            model: $newBrand,
            source: $request,
            inputName: 'picture',
        )->setCollectionName(MediaCollectionNames::OUTLET_PICTURE->value);

        return $newBrand->refresh();
    }

    /**
     * @param Outlet $outlet
     * @param Request $request
     * @return Outlet
     * @throws ValidationException
     */
    public function update(
        Outlet  $outlet,
        Request $request,
    ): Outlet
    {
        $validated = $this->validate(
            inputs: $request->input(),
            rules: [
                'name' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string'],
                'longitude' => ['required', 'numeric'],
                'latitude' => ['required', 'numeric'],
                'picture' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]
        );

        // update brand
        (new UpdateOutlet(outlet: $outlet, inputs: $validated))
            ->handle();

        // link brand picture
        linkedMediaCollection(
            model: $outlet,
            source: $request,
            inputName: 'picture',
        )->setCollectionName(MediaCollectionNames::OUTLET_PICTURE->value)
            ->deletePreviousMedia(true);

        return $outlet->refresh();
    }
}
