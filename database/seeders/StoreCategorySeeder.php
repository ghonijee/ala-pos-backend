<?php

namespace Database\Seeders;

use App\Models\StoreCategory;
use Illuminate\Database\Seeder;

class StoreCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                "name" => "Toko Retail"
            ],
            [
                "name" => "Makanan/Minuman"
            ],

        ];
        StoreCategory::insert($data);
    }
}
