<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Constant\UserStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;


class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $isMember = $this->faker->boolean;
        $userStatus = UserStatus::FREE;
        $expiredDate = null;
        if ($isMember) {
            $userStatus = UserStatus::PRO;
            $expiredDate = now()->addYear();
        }
        return [
            'fullname' => $this->faker->name(),
            'username' => $this->faker->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            "phone" => $this->faker->phoneNumber(),
            "user_status" => $userStatus,
            "is_member" => $isMember,
            "expired_date" => $expiredDate,
            'password' => "password", // password
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
