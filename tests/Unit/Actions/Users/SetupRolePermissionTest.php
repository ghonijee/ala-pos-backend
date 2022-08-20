<?php

use App\Actions\Users\SetupRolePermission;
use App\Constant\UserDefaultRole;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Database\Seeders\PermissionSeeder;

uses()->group('actions', 'actions.users');

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

it("can create default role for new store registered", function () {
    $user = User::factory()->create();
    $store = Store::factory()->create();
    $action = new SetupRolePermission($user, $store);
    $action->createRole();

    $userStore = Store::find($store->id);
    expect($userStore)->roles->not->toBeEmpty()->toHaveCount(2);
    expect($userStore->roles)->each(function ($role) {
        expect($role)->not->toBeEmpty();
    });
});

it("can assigmnet permission to default role", function () {
    $user = User::factory()->create();
    $store = Store::factory()->create();
    $action = new SetupRolePermission($user, $store);
    $action->createRole();
    $action->assigmnetPermission();

    $userStore = Store::find($store->id);
    expect($userStore->roles)->each(function ($role) {
        expect($role)->permissions->not->toBeEmpty();
    });
});

test("Can Setup Default role and permission for new user registered", function () {
    $user = User::factory()->create();
    $store = Store::factory()->create();
    SetupRolePermission::fromRegister($user, $store);

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
});

test("Can Setup Default role and permission for new user management", function () {
    // Setup default role
    $store = Store::factory()->create();
    SetupRolePermission::fromRegister(User::factory()->create(), $store);

    // init test data
    $user = User::factory()->create();
    $roleStaff = Role::where("store_id", $store->id)->where("name", UserDefaultRole::STAFF)->first();

    // Action Create
    SetupRolePermission::fromUserManagement($user, $store, $roleStaff->id);
    // Expetect Result
    $permission = PermissionRole::where("role_id", $roleStaff->id)->count();

    expect($store)->roles->not->toBeEmpty()->toHaveCount(2);
    expect($store->roles)->each(function ($role) {
        expect($role)->not->toBeEmpty();
    });
    expect($store->roles)->each(function ($role) {
        expect($role)->permissions->not->toBeEmpty();
    });
    expect($user->role)->name->toEqual($roleStaff->name)->id->toEqual($roleStaff->id);
    expect($user->role)->permissions->toHaveCount($permission);
});
