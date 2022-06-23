<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $store = [
            "name" => "Ala Store",
            "address" => "jl. Kemana aja",
            "phone" => "08172627282",
        ];

        $user = User::where('username', 'yunus')->first();

        $user->stores()->create($store);
    }
}
