<?php

namespace Database\Factories;

use App\Models\TransactionItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "key" => $this->faker->uuid(),
            "user_id" => 1,
            "store_id" => 1,
            "customer_name" => $this->faker->name,
            "customer_id" => null,
            "date" => date('Y-m-d'),
            "discount" => 0,
            "note" => $this->faker->sentence,
            "amount" => 100000,
            "received_money" => 100000,
            "change_money" => 0,
        ];
    }
}
