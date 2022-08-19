<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\PermissionSeeder;
use App\Actions\Users\SetupRolePermission;
use App\Models\PermissionRole;
use App\Models\RoleUser;

uses()->group('api', 'api.role');


beforeEach(function () {
    $user = User::factory()->create();

    actingAs($user);
    $this->seed(PermissionSeeder::class);
});

test("Can get list role by store id", function () {
    $store = Store::factory()->create();
    $user = Auth::user();
    $data = ["filter" => ["store_id", "=", $store->id]];
    SetupRolePermission::fromRegister($user, $store);
    $roles = Role::where("store_id", $store->id)->get();

    $response = $this->getJson(route("v1.role.index"), $data);
    expect($response)->toBeSuccess();

    expect($response->getData())->data->toHaveCount($roles->count());
});

test("Can get role and permission by user ID", function () {
    $store = Store::factory()->create();
    $user = Auth::user();
    SetupRolePermission::fromRegister($user, $store);
    $userRole = RoleUser::where("user_id", $user->id)->first();
    $rolePermission = PermissionRole::where("role_id", $userRole->role_id)->get();
    $response = $this->getJson(route("v1.role.userRole", ["id" => $user->id]));
    expect($response)->toBeSuccess();
    expect($response->getData())->data->permissions->toHaveCount($rolePermission->count());
    expect($response->getData())->data->id->toEqual($userRole->role_id);
});
