<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Product Factory
        $products = Product::factory()->count(100)->make();
        $store = Store::where('name', 'Ala Store')->first();
        $store->products()->saveMany(
            $products
        );
    }
}
