<?php

namespace Database\Seeders;

use App\Constant\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "username" => "yunus",
            "fullname" => "Ahmad Yunus A",
            "phone" => "081510897752",
            "password" => "password",
            "is_member" => true,
            "user_status" => UserStatus::PRO,
            "expired_date" => now()->addYear(),
        ]);
    }
}
