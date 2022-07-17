<?php

use App\Actions\Transactions\CreateTransactionItem;
use App\Models\Store;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;

uses()->group('actions', 'actions.transaction');

it("Can create new transaction Item", function () {
    $item = TransactionItem::factory()
        ->for(Transaction::factory()->create())
        ->for(
            Product::factory()
                ->for(Store::factory()->create())
                ->create()
        )->make();

    $instance = new CreateTransactionItem();
    $instance->execute($item->toArray(), $item->transaction);

    $transaction = Transaction::find($item->transaction->id);
    $transactionItem = $transaction->products->first();
    expect($transactionItem)->quantity->toEqual($item->quantity);
    expect($transactionItem)->price->toEqual($item->price);
});
