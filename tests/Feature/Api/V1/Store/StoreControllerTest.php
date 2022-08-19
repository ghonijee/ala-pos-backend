<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Store;
use App\Models\Permission;
use App\Constant\UserDefaultRole;
use Illuminate\Support\Facades\Auth;

uses()->group('api', 'api.store');


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
    $user = Auth::user();
    $response = $this->postJson(route("v1.store.store"), $data->toArray());

    $response->assertStatus(200);
    $store = Store::where("name", $data->name)->first();
    $roleOwner = Role::where("store_id", $store->id)->where("name", UserDefaultRole::OWNER)->first();

    expect($store)->roles->not->toBeEmpty()->toHaveCount(2);
    expect($store->roles)->each(function ($role) {
        expect($role)->not->toBeEmpty();
    });
    expect($store->roles)->each(function ($role) {
        expect($role)->permissions->not->toBeEmpty();
    });
    expect($user->role)->name->toEqual($roleOwner->name)->id->toEqual($roleOwner->id);
    expect($user->role)->permissions->toHaveCount(Permission::all()->count());

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

    expect($response)->toBeSuccess();
    expect($body->data)->name->toEqual($data->first()->name);
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
