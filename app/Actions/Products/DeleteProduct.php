<?php

namespace App\Actions\Products;

use App\Models\Products\Product;
use Winata\PackageBased\Abstracts\BaseAction;

class DeleteProduct extends BaseAction
{

    public function __construct(
        public readonly Product $product,
    )
    {
        parent::__construct();
    }

    /**
     * @return BaseAction
     */
    public function rules(): BaseAction
    {
        return $this;
    }

    public function handle(): bool
    {
        return $this->product->delete();
    }
}
