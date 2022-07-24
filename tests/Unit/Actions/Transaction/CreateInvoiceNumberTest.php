<?php

use App\Models\Store;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Actions\Transactions\CreateInvoiceNumber;
use Illuminate\Database\Eloquent\Factories\Sequence;

uses()->group("actions", "actions.transaction");

it("Can generate next sequence number invoice", function () {
    $today = now()->format('Y-m-d');
    $seq = 0;
    $data = Transaction::factory()
        ->count(5)
        ->state(
            new Sequence(
                ["sequence_number" => 1],
                ["sequence_number" => 2],
                ["sequence_number" => 3],
                ["sequence_number" => 4],
                ["sequence_number" => 5],
            )
        )
        ->has(TransactionItem::factory()
            ->count(5)
            ->for(
                Product::factory()
                    ->for(Store::factory()->create())
                    ->create()
            ), 'products')
        ->create(['date' => $today]);

    $class = new CreateInvoiceNumber();
    $class = $class->setup(Transaction::query(), 1);

    $stringExpect = "TR" . now()->format("Ymd") . "-0006";

    expect($class->generateNumber())->toEqual($stringExpect);
    expect($class->nextSequence())->toEqual(6);
})->group("actions.transaction.invoiceNumber");
