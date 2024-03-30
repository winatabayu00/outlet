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

class CreateProduct extends BaseAction
{
    use ValidationInput;

    protected ?Brand $brand = null;
    protected ?Outlet $outlet = null;

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
                'brand_id' => ['required', 'string'],
                'outlet_id' => ['nullable', 'string'],
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'gt:0'],
            ],
        );

        $this->brand = Brand::query()
            ->find($validated['brand_id']);

        if (!empty($validated['outlet_id'])) {
            $this->outlet = Outlet::query()
                ->where('brand_id', '=', $validated['brand_id']) // brand id outlet harus sama dengan brand id yang dikirim
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
        $newProduct = new Product();
        $newProduct->fill($input);

        if ($this->brand instanceof Brand){
            $newProduct->brand()->associate($this->brand);
        }

        if ($this->outlet instanceof Outlet){
            $newProduct->outlet()->associate($this->outlet);
        }
        $newProduct->save();

        return $newProduct->refresh();
    }
}
