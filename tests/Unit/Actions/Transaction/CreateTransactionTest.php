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

test("Product stock not changed when store not use stock opname feature", function () {
    $data = Transaction::factory()->make()->toArray();
    $productsList =  Product::factory()
        ->for(Store::factory()->state(["use_stock_opname" => false])->create())
        ->create();
    $dataItems = TransactionItem::factory()
        ->count(5)
        ->for(
            $productsList
        )->make();
    $data['products'] = $dataItems->toArray();
    $productExpectedOld = Product::first();

    $instance = new CreateTransaction();
    $instance->execute($data);
    $instance->createItems(new CreateTransactionItem(), false);

    $transaction = Transaction::where('key', $data['key'])->first();

    $productExpectedNew = Product::first();
    expect($productExpectedOld->stock)->toEqual($productExpectedNew->stock);

    expect($transaction)->key->toEqual($data['key']);
    expect($transaction)->products->not()->toBeEmpty();
    expect($instance->getTransaction())->products->not()->toBeEmpty();
    expect($instance->getTransaction())->products->toHaveCount($dataItems->count());
});

test("Product stock changed when store use stock opname feature", function () {
    $data = Transaction::factory()->make()->toArray();
    $productsList =  Product::factory()
        ->for(Store::factory()->state(["use_stock_opname" => false])->create())
        ->create();
    $dataItems = TransactionItem::factory()
        ->count(5)
        ->for(
            $productsList
        )->make();
    $data['products'] = $dataItems->toArray();

    $instance = new CreateTransaction();
    $instance->execute($data);
    $instance->createItems(new CreateTransactionItem(), true);

    $transaction = Transaction::where('key', $data['key'])->first();

    $productExpectedNew = Product::first();
    expect($productsList->stock)->not()->toEqual($productExpectedNew->stock);

    expect($transaction)->key->toEqual($data['key']);
    expect($transaction)->products->not()->toBeEmpty();
    expect($instance->getTransaction())->products->not()->toBeEmpty();
    expect($instance->getTransaction())->products->toHaveCount($dataItems->count());
});
