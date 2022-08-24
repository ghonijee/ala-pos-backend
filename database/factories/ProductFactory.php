<?php

namespace Database\Factories;

use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public $store;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $this->store = Store::all('id')->pluck('id');

        $price = collect([1000, 2000, 4000, 5000, 8000, 10000, 21000, 35000, 50000, 120000])->random();
        $cost = $price - 500;
        return [
            // "store_id" => $this->store->random(),
            "name" => $this->faker->sentence(2),
            "description" => $this->faker->realText(50),
            "is_active" => 1,
            "price" => $price,
            "reduce_price" => null,
            "code" => $this->faker->isbn13(),
            "cost" => $cost,
            "use_stock_opname" => 1,
            "stock" => rand(50, 100),
            "min_stock" => 5,
            "unit" => "pcs",
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ];
    }
}
