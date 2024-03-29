<?php

namespace App\Actions\Outlets;

use App\Models\Outlets\Outlet;
use Winata\PackageBased\Abstracts\BaseAction;

class DeleteOutlet extends BaseAction
{

    public function __construct(
        public readonly Outlet $outlet,
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

    /**
     * @return bool
     * @throws \Exception
     */
    public function handle(): bool
    {
        return $this->outlet->delete();
    }
}
