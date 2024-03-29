<?php

namespace Database\Seeders;

use App\Actions\Brands\CreateBrand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeedBrands extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'xiaomi',
            ],[
                'name' => 'samsung',
            ],
        ];

        foreach ($brands as $brand){
            (new CreateBrand(inputs: $brand))->handle();
        }
    }
}
