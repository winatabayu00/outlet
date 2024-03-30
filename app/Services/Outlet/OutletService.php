<?php

namespace App\Services\Outlet;

use App\Actions\Outlets\CreateOutlet;
use App\Actions\Outlets\UpdateOutlet;
use App\Enums\Media\MediaCollectionNames;
use App\Models\Outlets\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Winata\PackageBased\Abstracts\BaseService;

class OutletService extends BaseService
{

    /**
     * @param Request $request
     * @return Outlet
     * @throws ValidationException
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
                'picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]
        );

        DB::beginTransaction();

        // create new brand
        $newBrand = (new CreateOutlet(inputs: $validated))
            ->handle();

        // link brand picture
        linkedMediaCollection(
            model: $newBrand,
            source: $request,
            inputName: 'picture',
        )->setCollectionName(MediaCollectionNames::OUTLET_PICTURE->value);
        DB::commit();

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
                'picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]
        );

        DB::beginTransaction();
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

        DB::commit();

        return $outlet->refresh();
    }
}
