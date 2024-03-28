<?php

namespace App\Actions\Outlets;

use App\Models\Outlets\Outlet;
use Illuminate\Validation\ValidationException;
use Winata\PackageBased\Abstracts\BaseAction;
use Winata\PackageBased\Concerns\ValidationInput;

class UpdateOutlet extends BaseAction
{
    use ValidationInput;

    public function __construct(
        public readonly Outlet $outlet,
        public readonly array $inputs
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
                'address' => ['required', 'string'],
                'longitude' => ['required', 'numeric'],
                'latitude' => ['required', 'numeric'],
            ]
        );
        return $this;
    }

    /**
     * @return Outlet
     */
    public function handle(): Outlet
    {
        $input = Outlet::getFillableAttribute($this->inputs);
        $this->outlet->update($input);
        return $this->outlet->refresh();
    }
}
