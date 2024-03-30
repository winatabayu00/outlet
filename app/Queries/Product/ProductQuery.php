<?php

namespace App\Queries\Product;

use App\Models\Products\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Winata\PackageBased\Abstracts\QueryBuilderAbstract;

class ProductQuery extends QueryBuilderAbstract
{

    public function getBaseQuery(): Builder
    {
        return Product::query();
    }

    protected function applyParameters(): void
    {

    }
}
