<?php

namespace App\Actions\Outlets;

use App\Enums\ResponseCode\ResponseCode;
use App\Models\Outlets\Outlet;
use Illuminate\Validation\ValidationException;
use Winata\Core\Response\Exception\BaseException;
use Winata\PackageBased\Abstracts\BaseAction;
use Winata\PackageBased\Concerns\ValidationInput;

class UpdateOutlet extends BaseAction
{
    use ValidationInput;

    public function __construct(
        public readonly Outlet $outlet,
        public readonly array  $inputs
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
                'name' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string'],
                'longitude' => ['required', 'numeric'],
                'latitude' => ['required', 'numeric'],
            ]
        );

        // checking unique name (brand_id, name)
        $isUniqueOutlet = Outlet::query()
            ->where('id', '!=', $this->outlet->id)
            ->where('brand_id', '=', $this->outlet->brand_id)
            ->where('name', '=', $validated['name'])
            ->exists();

        if ($isUniqueOutlet) {
            throw new BaseException(
                rc: ResponseCode::ERR_ENTITY_ALREADY_EXISTS,
                message: 'Outlet name has been used'
            );
        }
        return $this;
    }

    /**
     * @return Outlet
     */
    public function handle(): Outlet
    {
        $input = Outlet::getFillableAttribute($this->validatedData);
        $this->outlet->update($input);
        return $this->outlet->refresh();
    }
}
