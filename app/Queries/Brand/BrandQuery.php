<?php

namespace App\Queries\Brand;

use App\Models\Brands\Brand;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Winata\PackageBased\Abstracts\QueryBuilderAbstract;

class BrandQuery extends QueryBuilderAbstract
{

    public function getBaseQuery(): Builder
    {
        return Brand::query();
    }

    protected function applyParameters(): void
    {

    }
}
