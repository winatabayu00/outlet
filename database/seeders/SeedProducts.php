<?php

namespace Database\Seeders;

use App\Models\Brands\Brand;
use App\Models\Outlets\Outlet;
use App\Services\Product\ProductService;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

class SeedProducts extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var Brand $brand */
        $brand = Brand::query()
            ->firstOrFail();

        $outlets = Outlet::query()
            ->where('brand_id', '=', $brand->id)
            ->pluck('id')->toArray();

        $listPrice = range(10000, 100000, 10000);

        for ($i = 1; $i <= 10; $i++) {
            $productData = [
                'brand_id' => $brand->id,
                'outlet_id' => $outlets[rand(0, count($outlets) - 1)],
                'name' => 'product ' . $i,
                'price' => $listPrice[rand(0, count($listPrice) - 1)]
            ];

            $service = new ProductService();
            $service->create(new Request($productData));
        }
    }
}
