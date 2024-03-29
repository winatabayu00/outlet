<?php

namespace App\Http\Resources\Outlets;

use App\Enums\Media\MediaCollectionNames;
use App\Models\Outlets\Outlet;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Outlet $resource
 * */
class OutletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $picture = $this->resource->getFirstMediaUrl(MediaCollectionNames::OUTLET_PICTURE->value);

        return [
            'picture' => $this->when(!empty($picture), $picture, null),
            'name' => $this->resource->name,
            'address' => $this->resource->address,
            'longitude' => $this->resource->longitude,
            'latitude' => $this->resource->latitude,
        ];
    }
}
