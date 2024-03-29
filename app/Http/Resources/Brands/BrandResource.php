<?php

namespace App\Http\Resources\Brands;

use App\Enums\Media\MediaCollectionNames;
use App\Models\Brands\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Brand $resource
 * */
class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $logo = $this->resource->getFirstMediaUrl(MediaCollectionNames::BRAND_LOGO->value);

        return [
            'logo' => $this->when(!empty($logo), $logo, null),
            'name' => $this->resource->name,
        ];
    }
}
