<?php

namespace App\Actions\Products;

use App\Enums\ResponseCode\ResponseCode;
use App\Models\Brands\Brand;
use App\Models\Outlets\Outlet;
use App\Models\Products\Product;
use Illuminate\Validation\ValidationException;
use Winata\Core\Response\Exception\BaseException;
use Winata\PackageBased\Abstracts\BaseAction;
use Winata\PackageBased\Concerns\ValidationInput;

class UpdateProduct extends BaseAction
{
    use ValidationInput;
    protected ?Outlet $outlet = null;

    public function __construct(
        public readonly Product $product,
        public readonly array $inputs,
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
                'outlet_id' => ['nullable', 'string'],
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'gt:0'],
            ],
        );

        if (!empty($validated['outlet_id'])) {
            $this->outlet = Outlet::query()
                ->where('brand_id', '=', $this->product->brand_id) // brand id outlet harus sama dengan brand id yang dikirim
                ->where('id', '=', $validated['outlet_id'])
                ->first();

            // check outlet
            if (!$this->outlet instanceof Outlet){
                // outlet is missing
                throw new BaseException(
                    rc: ResponseCode::ERR_ENTITY_NOT_FOUND,
                    message: 'We cannot found the outlet'
                );
            }
        }

        return $this;
    }

    /**
     * @return Product
     */
    public function handle(): Product
    {
        $input = Product::getFillableAttribute($this->inputs);
        $this->product->fill($input);

        // possible to change outlet where the product has been
        if ($this->outlet instanceof Outlet){
            $this->product->outlet()->associate($this->outlet);
        }else{
            $this->product->outlet_id = $this->validatedData['outlet_id'];
        }

        $this->product->save();

        return $this->product->refresh();
    }
}
