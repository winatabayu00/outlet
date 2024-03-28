<?php

namespace App\Actions\Products;

use App\Models\Brands\Brand;
use App\Models\Products\Product;
use Illuminate\Validation\ValidationException;
use Winata\PackageBased\Abstracts\BaseAction;
use Winata\PackageBased\Concerns\ValidationInput;

class CreateProduct extends BaseAction
{
    use ValidationInput;

    protected ?Brand $brand = null;
    protected ?Brand $outlet = null;

    public function __construct(
        public readonly array $inputs
    )
    {
        parent::__construct();
    }

    /**
     * @return BaseAction
     * @throws ValidationException
     */
    public function rules(): BaseAction
    {
        $validated = $this->validate(
            inputs: $this->inputs,
            rules: [
                'brand_id' => ['required', 'string'],
                'outlet_id' => ['nullable', 'string'],
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'gt:0'],
            ],
        );

        if (!empty($validated['brand_id'])) {
            $this->brand = Brand::query()
                ->find($validated['brand_id']);
        }

        if (!empty($validated['outlet_id'])) {
            $this->outlet = Brand::query()
                ->find($validated['outlet_id']);
        }

        return $this;
    }

    /**
     * @return Product
     */
    public function handle(): Product
    {
        $input = Product::getFillableAttribute($this->inputs);
        $newProduct = new Product();
        $newProduct->fill($input);

        if ($this->brand instanceof Brand){
            $newProduct->brand()->associate($this->brand);
        }

        if ($this->outlet instanceof Brand){
            $newProduct->outlet()->associate($this->outlet);
        }
        $newProduct->save();

        return $newProduct->refresh();
    }
}
