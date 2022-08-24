<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Store;
use App\Models\Permission;
use App\Constant\UserDefaultRole;
use Database\Seeders\UserExistSetPermissionRoleSeeder;

test("Can set Permission and role for existing user on database", function () {
    $users = User::factory()->count(5)->has(Store::factory()->count(1), 'stores')->create();

    $this->seed(UserExistSetPermissionRoleSeeder::class);

    $users->each(function ($user) {
        $roleOwner = Role::where("store_id", $user->mainStore->id)->where("name", UserDefaultRole::OWNER)->first();
        expect($user->role)->name->toEqual($roleOwner->name)->id->toEqual($roleOwner->id);
        expect($user->role)->permissions->toHaveCount(Permission::all()->count());
    });
});
