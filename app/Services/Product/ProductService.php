<?php

namespace App\Services\Product;

use App\Actions\Products\CreateProduct;
use App\Actions\Products\UpdateProduct;
use App\Enums\Media\MediaCollectionNames;
use App\Models\Products\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Winata\PackageBased\Abstracts\BaseService;

class ProductService extends BaseService
{
    /**
     * @param Request $request
     * @return Product
     * @throws ValidationException
     */
    public function create(
        Request $request
    ): Product
    {
        $validated = $this->validate(
            inputs: $request->input(),
            rules: [
                'brand_id' => ['required', 'string'],
                'outlet_id' => ['nullable', 'string'],
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'gt:0'],
                'picture' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]
        );

        // create new brand
        $newBrand = (new CreateProduct(inputs: $validated))
            ->handle();

        // link brand picture
        linkedMediaCollection(
            model: $newBrand,
            source: $request,
            inputName: 'picture',
        )->setCollectionName(MediaCollectionNames::PRODUCT_PICTURE->value);

        return $newBrand->refresh();
    }

    /**
     * @param Product $product
     * @param Request $request
     * @return Product
     * @throws ValidationException
     */
    public function update(
        Product $product,
        Request $request,
    ): Product
    {
        $validated = $this->validate(
            inputs: $request->input(),
            rules: [
                'outlet_id' => ['nullable', 'string'],
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'gt:0'],
                'picture' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]
        );

        // update brand
        (new UpdateProduct(product: $product, inputs: $validated))
            ->handle();

        // link brand picture
        linkedMediaCollection(
            model: $product,
            source: $request,
            inputName: 'picture',
        )->setCollectionName(MediaCollectionNames::PRODUCT_PICTURE->value)
            ->deletePreviousMedia(true);

        return $product->refresh();
    }
}
