<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $price = random_int(1000, 100000);
        $qty = random_int(1, 10);
        return [
            // "transaction_id" => 1,
            "store_id" => 1,
            "product_id" => 1,
            "product_name" => $this->faker->sentence(2),
            "price" => $price,
            "product_cost" => $price - 500,
            "discount_price" => 0,
            "discount_percentage" => null,
            "note" => null,
            "quantity" => $qty,
            "amount" => $price * $qty,
        ];
    }
}
