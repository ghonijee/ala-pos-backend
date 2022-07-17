<?php

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

beforeEach(function () {
    $user = User::factory()->create();

    actingAs($user);
});

it("Can get list store by store with paginate", function ($take) {
    Store::factory()->count(100)->create();

    $response = $this->getJson(route("v1.store.index") . "?take=$take");

    $response->assertStatus(200);
    $body = $response->getData();
    expect(collect($body->data)->count())->toEqual($take);
    expect($response)->toBeSuccess();
})->with([10, 15, 20]);

it("Can create new store to tabel", function () {
    $data = Store::factory()->make();
    $data->user_id = Auth::id();
    $response = $this->postJson(route("v1.store.store"), $data->toArray());

    $response->assertStatus(200);
    $body = $response->getData();
    expect($response)->toBeSuccess();
});

it("Can show store by ID", function () {
    // Store::factory()->create();
    $data = Store::factory()->create();

    $response = $this->getJson(route("v1.store.show", ['store' => $data->id]));

    $response->assertStatus(200);

    $body = $response->getData();

    expect((array) $body->data)->toEqual($data->toArray());
    expect($response)->toBeSuccess();
});

it("Can get main store by User Login", function () {
    // Store::factory()->create();
    $data = Store::factory()->count(5)->make();

    Auth::user()->stores()->saveMany($data);

    $response = $this->getJson(route("v1.store.main"));

    $response->assertStatus(200);

    $body = $response->getData();

    expect((array) $body->data)->name->toEqual($data->first()->name);
    expect($response)->toBeSuccess();
});

it("Can update store by ID", function () {
    $data = Store::factory()->create();

    // Update Name
    $data->name = "Change Name";

    $response = $this->putJson(route("v1.store.update", ['store' => $data->id]), $data->toArray());

    $response->assertStatus(200);
    $body = $response->getData();
    expect((array) $body->data)->toEqual($data->toArray());
    expect($response)->toBeSuccess();
});

it("Can delete store by ID", function () {
    $data = Store::factory()->create();

    $response = $this->deleteJson(route("v1.store.destroy", ['store' => $data->id]));

    $response->assertStatus(200);
    $body = $response->getData();

    expect($response)->toBeSuccess();
});
