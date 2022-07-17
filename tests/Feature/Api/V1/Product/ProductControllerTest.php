<?php

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Arr;

beforeEach(function () {
    $user = User::factory()->make();

    actingAs($user);
});

it("Can get list product by store with paginate", function ($take) {
    Store::factory()->hasProducts(100)->create();

    $response = $this->getJson(route("product.index") . "?take=$take");

    $response->assertStatus(200);
    $body = $response->getData();
    expect(collect($body->data)->count())->toEqual($take);
    expect($response)->toBeSuccess();
})->with([10, 15, 20]);

it("Can store new product to store", function () {
    Store::factory()->create();
    $data = Product::factory()->make();

    $response = $this->postJson(route("product.store"), $data->toArray());

    $response->assertStatus(200);
    $body = $response->getData();
    expect($response)->toBeSuccess();
});

it("Can show product by ID", function () {
    Store::factory()->create();
    $data = Product::factory()->create();

    $response = $this->getJson(route("product.show", ['product' => $data->id]));

    $response->assertStatus(200);

    $body = $response->getData();

    expect((array) $body->data)->toEqual($data->toArray());
    expect($response)->toBeSuccess();
});

it("Can update product by ID", function () {
    Store::factory()->create();
    $data = Product::factory()->create();

    // Update Name
    $data->name = "Change Name";

    $response = $this->putJson(route("product.show", ['product' => $data->id]), $data->toArray());

    $response->assertStatus(200);
    $body = $response->getData();
    expect((array) $body->data)->toEqual($data->toArray());
    expect($response)->toBeSuccess();
});

it("Can delete product by ID", function () {
    Store::factory()->create();
    $data = Product::factory()->create();

    $response = $this->deleteJson(route("product.show", ['product' => $data->id]));

    $response->assertStatus(200);
    $body = $response->getData();

    expect($response)->toBeSuccess();
});
