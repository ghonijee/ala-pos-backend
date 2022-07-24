<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $store = Store::factory()->create();
        $store = Store::where('name', 'Ala Store')->first();

        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');
        $days = now()->subDays(2)->format('Y-m-d');
        $daysAgain = now()->subDays(3)->format('Y-m-d');

        Transaction::factory()
            ->count(50)
            ->state(['store_id' => $store->id])
            ->state(new Sequence(
                ['date' => now()->format('Y-m-d')],
                ['date' => now()->subDays(1)->format('Y-m-d')],
                ['date' => now()->subDays(2)->format('Y-m-d')],
                ['date' => now()->subDays(3)->format('Y-m-d')],
                ['date' => now()->subDays(4)->format('Y-m-d')],
            ))
            ->sequence(function ($sequence) {
                $stringExpect = "TR" . now()->format("Ymd") . sprintf("-%04d", $sequence->index + 1);
                return ['invoice_number' => $stringExpect];
            })
            ->has(TransactionItem::factory()
                ->count(5)
                ->for(
                    Product::factory()
                        ->state(['store_id' => $store->id])
                        ->create()
                ), 'products')
            ->create();
    }
}
