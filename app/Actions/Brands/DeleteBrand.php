<?php

namespace App\Actions\Brands;

use App\Models\Brands\Brand;
use Winata\PackageBased\Abstracts\BaseAction;
use Winata\PackageBased\Concerns\ValidationInput;

class DeleteBrand extends BaseAction
{
    use ValidationInput;

    public function __construct(
        public readonly Brand $brand,
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
        return $this->brand->delete();
    }
}
