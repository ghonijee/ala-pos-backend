<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => random_int(1, 100),
            "name" => $this->faker->company,
            "phone" => $this->faker->phoneNumber,
            "address" => $this->faker->address,
            // "image_url" =>
        ];
    }
}
