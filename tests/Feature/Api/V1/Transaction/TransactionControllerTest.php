<?php

use App\Models\Product;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;

uses()->group('api', 'api.transaction');


beforeEach(function () {
    $user = User::factory()->create();

    actingAs($user);
});

it("Can create transaction", function () {
    $data = Transaction::factory()->make()->toArray();
    $dataItems = TransactionItem::factory()
        ->count(5)
        ->for(
            Product::factory()
                ->for(Store::factory()->create())
                ->create()
        )->make()
        ->toArray();
    $data['items'] = $dataItems;

    $response = $this->postJson(route('v1.transaction.store'), $data);
    $transaction = Transaction::where('key', $data['key'])->first();
    $transactionItems = $transaction->products;
    $response->assertStatus(200);
    expect($response)->toBeSuccess();
    expect($transaction)->key->toEqual($data['key']);
    expect($transactionItems->count())->toEqual(collect($dataItems)->count());
});
