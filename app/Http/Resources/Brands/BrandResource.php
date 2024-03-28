<?php

namespace App\Http\Resources\Brands;

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
        return [
            'logo' => '',
            'name' => $this->resource->name,
        ];
    }
}
