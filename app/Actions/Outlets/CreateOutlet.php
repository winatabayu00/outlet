<?php

namespace App\Actions\Outlets;

use App\Enums\ResponseCode\ResponseCode;
use App\Models\Brands\Brand;
use App\Models\Outlets\Outlet;
use Illuminate\Validation\ValidationException;
use Winata\Core\Response\Exception\BaseException;
use Winata\PackageBased\Abstracts\BaseAction;
use Winata\PackageBased\Concerns\ValidationInput;

class CreateOutlet extends BaseAction
{
    use ValidationInput;

    protected ?Brand $brand;

    public function __construct(
        public readonly array $inputs
    )
    {
        parent::__construct();
    }

    /**
     * @return BaseAction
     * @throws BaseException
     * @throws ValidationException
     */
    public function rules(): BaseAction
    {
        $validated = $this->validate(
            inputs: $this->inputs,
            rules: [
                'brand_id' => ['required', 'string', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string'],
                'longitude' => ['required', 'numeric'],
                'latitude' => ['required', 'numeric'],
            ]
        );

        // check brand
        $this->brand = Brand::query()
            ->find($validated['brand_id']);

        if (!$this->brand instanceof Brand){
            throw new BaseException(
                rc: ResponseCode::ERR_ENTITY_NOT_FOUND,
                message: 'The brand does not exists'
            );
        }

        return $this;
    }

    /**
     * @return Outlet
     */
    public function handle(): Outlet
    {
        $input = Outlet::getFillableAttribute($this->inputs);
        /** @var Outlet $outlet */
        $outlet = Outlet::query()
            ->create($input);
        return $outlet;
    }
}
