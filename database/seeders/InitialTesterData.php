<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InitialTesterData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(StoreSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(TransactionSeeder::class);
    }
}
