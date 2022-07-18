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

it("Can get list of transaction on today with paginate", function ($take, $page, $count) {
    $store = Store::factory()->create();
    $today = now()->format('Y-m-d');

    $data = Transaction::factory()
        ->count(20)
        ->state(['store_id' => $store->id])
        ->has(TransactionItem::factory()
            ->count(5)
            ->for(
                Product::factory()
                    ->state(['store_id' => $store->id])
                    ->create()
            ), 'products')
        ->create(['date' => $today]);

    $filter = [["store_id", "=", $store->id], "AND", ['date', '=', $today]];
    $url = route('v1.transaction.index', ["filter" => json_encode($filter), "take" => $take, "page" => $page]);

    $response = $this->getJson($url);

    $response->assertStatus(200);
    expect($response)->toBeSuccess();
    $body = $response->getData();
    expect(collect($body->data)->count())->toEqual($count);
    expect($body->data)->each(function ($item) use ($today) {
        expect($item)->products->toHaveLength(5);
        expect($item->value->date)->toEqual($today);
    });
})->with([
    ["take" => 10, "page" => 1, "count" => 10],
    ["take" => 10, "page" => 2, "count" => 10],
    ["take" => 15, "page" => 1, "count" => 15],
    ["take" => 15, "page" => 2, "count" => 5],
]);
