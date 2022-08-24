<?php

use App\Actions\Transactions\CreateTransaction;
use App\Actions\Transactions\CreateTransactionItem;
use App\Models\Store;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;

uses()->group('actions', 'actions.transaction');

it("Can create new transaction", function () {
    $data = Transaction::factory()->make()->toArray();
    $dataItems = TransactionItem::factory()
        ->count(5)
        ->for(
            Product::factory()
                ->for(Store::factory()->create())
                ->create()
        )->make();
    $data['products'] = $dataItems->toArray();
    $instance = new CreateTransaction();
    $instance->execute($data);
    $instance->createItems(new CreateTransactionItem());

    $transaction = Transaction::where('key', $data['key'])->first();

    expect($transaction)->key->toEqual($data['key']);
    expect($transaction)->products->not()->toBeEmpty();
    expect($instance->getTransaction())->products->not()->toBeEmpty();
    expect($instance->getTransaction())->products->toHaveCount($dataItems->count());
});
