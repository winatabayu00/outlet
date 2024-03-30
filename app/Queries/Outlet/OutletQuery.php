<?php

namespace App\Queries\Outlet;

use App\Models\Outlets\Outlet;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Winata\PackageBased\Abstracts\QueryBuilderAbstract;

class OutletQuery extends QueryBuilderAbstract
{

    public function getBaseQuery(): Builder
    {
        return Outlet::query();
    }

    protected function applyParameters(): void
    {

    }
}
