<?php

namespace Database\Seeders;

use App\Models\Brands\Brand;
use App\Services\Outlet\OutletService;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Fluent;

class SeedOutlets extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var Brand $brand1 */
        $brand1 = Brand::query()
            ->firstOrFail();

        /** @var Brand $brand2 */
        $brand2 = Brand::query()
            ->where('id', '!=', $brand1->id)
            ->firstOrFail();

        $outlets = [
            [
                'name' => 'outlet 1',
                'brand_id' => $brand1->id,
            ],[
                'name' => 'outlet 2',
                'brand_id' => $brand2->id,
            ],
        ];

        foreach ($outlets as $outlet){
            $outlet = new Fluent($outlet);
            $outletData = new Request([
                'brand_id' => $outlet->brand_id,
                'name' => $outlet->name,
                'address' => fake()->address,
                'longitude' => fake()->longitude,
                'latitude' => fake()->latitude,
            ]);

            $service = new OutletService();
            $service->create($outletData);
        }
    }
}
