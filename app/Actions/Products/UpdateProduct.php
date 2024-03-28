<?php

namespace App\Actions\Products;

use App\Models\Products\Product;
use Illuminate\Validation\ValidationException;
use Winata\PackageBased\Abstracts\BaseAction;
use Winata\PackageBased\Concerns\ValidationInput;

class UpdateProduct extends BaseAction
{
    use ValidationInput;

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
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'gt:0'],
            ],
        );
        return $this;
    }

    /**
     * @return Product
     */
    public function handle(): Product
    {
        $input = Product::getFillableAttribute($this->inputs);
        $this->product->update($input);
        return $this->product->refresh();
    }
}
