<?php

use App\Models\Product;
use App\Models\Store;
use App\Models\User;

uses()->group('api', 'api.product');


beforeEach(function () {
    $user = User::factory()->make();

    actingAs($user);
});

it("Can get list product by store with paginate", function ($take) {
    Store::factory()->hasProducts(100)->create();

    $response = $this->getJson(route("v1.product.index") . "?take=$take");

    $response->assertStatus(200);
    $body = $response->getData();
    expect(collect($body->data)->count())->toEqual($take);
    expect($response)->toBeSuccess();
})->with([10, 15, 20]);

it("Can store new product to store", function () {

    $data = Product::factory()->for(Store::factory()->create())->make();

    $response = $this->postJson(route("v1.product.store"), $data->toArray());

    $response->assertStatus(200);
    $body = $response->getData();
    expect($response)->toBeSuccess();
});

it("Can show product by ID", function () {

    $data = Product::factory()->for(Store::factory()->create())->create();

    $response = $this->getJson(route("v1.product.show", ['product' => $data->id]));

    $response->assertStatus(200);

    $body = $response->getData();

    expect((array) $body->data)->toEqual($data->toArray());
    expect($response)->toBeSuccess();
});

it("Can update product by ID", function () {
    $data = Product::factory()->for(Store::factory()->create())->create();


    // Update Name
    $data->name = "Change Name";

    $response = $this->putJson(route("v1.product.show", ['product' => $data->id]), $data->toArray());

    $response->assertStatus(200);
    $body = $response->getData();
    expect((array) $body->data)->toEqual($data->toArray());
    expect($response)->toBeSuccess();
});

it("Can delete product by ID", function () {
    $data = Product::factory()->for(Store::factory()->create())->create();

    $response = $this->deleteJson(route("v1.product.show", ['product' => $data->id]));

    $response->assertStatus(200);
    $body = $response->getData();

    expect($response)->toBeSuccess();
});
