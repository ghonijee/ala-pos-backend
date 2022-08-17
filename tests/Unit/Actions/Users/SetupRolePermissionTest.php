<?php

use App\Actions\Users\SetupRolePermission;
use App\Models\Permission;
use App\Models\PermissionRole;
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
        expect($role)->permissions->not->toBeEmpty()->dd();
    });
});
