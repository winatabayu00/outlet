<?php

namespace App\Actions\Products;

use App\Models\Brands\Brand;
use App\Models\Products\Product;
use Illuminate\Validation\ValidationException;
use Winata\PackageBased\Abstracts\BaseAction;
use Winata\PackageBased\Concerns\ValidationInput;

class UpdateProduct extends BaseAction
{
    use ValidationInput;
    protected ?Brand $outlet = null;

    public function __construct(
        public readonly Product $product,
        public readonly array $inputs,
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
        $this->validate(
            inputs: $this->inputs,
            rules: [
                'outlet_id' => ['nullable', 'string'],
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'gt:0'],
            ],
        );

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
        $this->product->fill($input);

        // possible to change outlet where the product has been
        if ($this->outlet instanceof Brand){
            $this->product->outlet()->associate($this->outlet);
        }

        $this->product->save();

        return $this->product->refresh();
    }
}
