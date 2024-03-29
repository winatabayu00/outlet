<?php

namespace App\Actions\Brands;

use App\Enums\ResponseCode\ResponseCode;
use App\Models\Brands\Brand;
use Illuminate\Validation\ValidationException;
use Winata\Core\Response\Exception\BaseException;
use Winata\PackageBased\Abstracts\BaseAction;
use Winata\PackageBased\Concerns\ValidationInput;

class CreateBrand extends BaseAction
{
    use ValidationInput;

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
                'name' => ['required', 'string', 'max:255']
            ],
        );

        // check brand duplicate
        // can use exists validation but i want handle duplicate data with mapping response code
        $brandExists = Brand::query()
            ->where('name', '=', $validated['name'])
            ->first();

        if ($brandExists instanceof Brand){
            throw new BaseException(
                rc: ResponseCode::ERR_ENTITY_ALREADY_EXISTS,
                message: 'The brand name has been used'
            );
        }

        return $this;
    }

    /**
     * @return Brand
     */
    public function handle(): Brand
    {
        $input = Brand::getFillableAttribute($this->validatedData);

        /** @var Brand $newBrand */
        $newBrand = Brand::query()
            ->create($input);
        return $newBrand->refresh();
    }
}
