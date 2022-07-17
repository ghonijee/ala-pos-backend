<?php

use App\Actions\Transactions\CreateTransaction;
use App\Actions\Transactions\CreateTransactionItem;
use App\Models\Store;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;

uses()->group('actions', 'actions.transaction');

it("Can create new transaction Item", function () {
    $data = Transaction::factory()->make()->toArray();
    $dataItems = TransactionItem::factory()
        ->count(5)
        ->for(
            Product::factory()
                ->for(Store::factory()->create())
                ->create()
        )->make();
    $data['items'] = $dataItems->toArray();

    $instance = new CreateTransaction();
    $instance->execute($data);
    $transaction = Transaction::where('key', $data['key'])->first();
    expect($transaction)->key->toEqual($data['key']);
    expect($transaction)->products->toBeEmpty();
    $instance->createItems(new CreateTransactionItem());
    expect($instance->getTransaction())->products->toHaveCount($dataItems->count());
});
