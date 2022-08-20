<?php

use App\Models\Permission;
use App\Models\User;
use Database\Seeders\PermissionSeeder;

test("Can get list all permission", function () {
    $this->seed(PermissionSeeder::class);
    $permissionExpected = Permission::all();
    $user = User::factory()->make();
    actingAs($user);

    $response = $this->getJson(route("v1.permission.index"));
    expect($response)->toBeSuccess();
    expect($response)->assertStatus(200);
    expect($response->getData())->data->toHavecount($permissionExpected->count());
});

test("Can't get list all permission with error response", function () {
    $this->seed(PermissionSeeder::class);
    $user = User::factory()->make();
    actingAs($user);
    $data = ["filter" => ["test", "=", 1]];
    $response = $this->getJson(route("v1.permission.index", $data));
    expect($response)->toBeFailed();
    expect($response)->assertStatus(500);
});
