<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Store;
use App\Models\RoleUser;
use App\Models\PermissionRole;
use App\Constant\UserDefaultRole;
use App\Actions\Users\SetupRolePermission;

uses()->group('api', 'api.user');


beforeEach(function () {
    $user = User::factory()->create();

    actingAs($user);
});

test("User can list another user from same store", function () {

    $usersId = User::factory()->count(3)->create()->pluck('id');
    $store = Store::factory()->create();
    $store->users()->sync($usersId);

    $response = $this->getJson(route('v1.user.userStaff', ['store' => $store->id]));

    $response->assertStatus(200);
    $body = $response->getData();
    expect($body)->data->toHaveCount(3);
    expect($response)->toBeSuccess();
});

test("User can show profile data", function () {

    $user = User::factory()->create();

    $response = $this->getJson(route('v1.user.show', ['user' => $user->id]));

    $response->assertStatus(200);
    $body = $response->getData();
    expect($body->data)->fullname->toEqual($user->fullname);
    expect($response)->toBeSuccess();
});

test("User can't show profile not found", function () {

    $user = User::factory()->create();

    $response = $this->getJson(route('v1.user.show', ['user' => 100]));

    $response->assertStatus(404);
    $body = $response->getData();
    expect($body->data)->toBeNull();
    expect($response)->toBeFailed();
});

test("User can't add new user becouse invalid form data", function () {

    $userAdmin = User::factory()->create();
    $store = Store::factory()->create();
    SetupRolePermission::fromRegister($userAdmin, $store);
    $roleStaff = Role::where("store_id", $store->id)->where("name", UserDefaultRole::STAFF)->first();

    // init test data
    $newUser = [
        "fullname" => "fullname test",
        "username" => "test-username",
        "phone" => "081627272728",
        "email" => "tets@mail.com",
    ];

    $response = $this->postJson(route('v1.user.store'), $newUser);
    // Assert Create User
    $response->assertStatus(422);
    expect($response)->toBeFailed();
    expect($response->getData())->message->toContain("password");
});

test("User can add new user with role staff/crew", function () {

    $userAdmin = User::factory()->create();
    $store = Store::factory()->create();
    SetupRolePermission::fromRegister($userAdmin, $store);
    $roleStaff = Role::where("store_id", $store->id)->where("name", UserDefaultRole::STAFF)->first();

    // init test data
    $newUser = [
        "fullname" => "fullname test",
        "username" => "test-username",
        "phone" => "081627272728",
        "email" => "tets@mail.com",
        "password" => "12345678",
        "role_id" => $roleStaff->id,
        "store_id" => $store->id,
    ];

    $response = $this->postJson(route('v1.user.store'), $newUser);
    // Assert Create User
    $response->assertStatus(200);
    expect($response)->toBeSuccess();
    expect($response->getData())->data->fullname->toEqual($newUser['fullname']);
    $newUserResponse = (array) $response->getData()->data;

    // Assert Test login new User
    $data = [
        "username" => $newUser["username"],
        "password" => $newUser["password"],
        "device_name" => "pest",
    ];

    $response = $this->post('api/v1/mobile/sign-in', $data);
    $response->assertStatus(200);
    expect($response)->toBeSuccess();
    expect($response->getData())->data->toHaveProperty("personal_access_token");
    expect($response->getData())->data->toHaveProperty("user");

    // Assert get Role & permission user
    $userFind = User::find($newUserResponse['id']);
    $userRole = RoleUser::where("user_id", $userFind->id)->first();
    $rolePermission = PermissionRole::where("role_id", $userRole->role_id)->get();
    $response = $this->getJson(route("v1.role.userRole", ["id" => $userFind->id]));
    expect($response)->toBeSuccess();
    expect($response->getData())->data->permissions->toHaveCount($rolePermission->count());
    expect($response->getData())->data->id->toEqual($userRole->role_id);

    // Assert user result has store
    expect($userFind)->mainStore->id->toEqual($store->id);
});

test("User can update profile data", function () {

    $user = User::factory()->create();

    $updateData = [
        "fullname" => "Update fullname",
        "username" => "Update username",
        "phone" => "Update phone",
        "email" => "Update email",
    ];
    $response = $this->putJson(route('v1.user.update', ['user' => $user->id]), $updateData);

    $response->assertStatus(200);
    $body = $response->getData();
    expect($body->data)->fullname->toEqual($updateData["fullname"]);
    expect($response)->toBeSuccess();
});

test("User can't update profile data becouse form data invalid", function () {

    $user = User::factory()->create();

    $updateData = [
        "fullname" => "Update fullname",
        "username" => "Username",
        "phone" => null,
        "email" => "Update email",
    ];
    $response = $this->putJson(route('v1.user.update', ['user' => $user->id]), $updateData);
    $response->assertStatus(422);
    expect($response)->toBeFailed();
});

test("User can change password", function () {

    $user = User::factory()->create();

    $data = [
        "new_password" => "password_new",
        "old_password" => "password",
    ];
    $response = $this->postJson(route('v1.change.password', ['id' => $user->id]), $data);

    $response->assertStatus(200);
    expect($response)->toBeSuccess();
});

test("User can't change password becouse old password invalid", function () {

    $user = User::factory()->create();

    $data = [
        "new_password" => "password_new",
        "old_password" => "password_valid",
    ];
    $response = $this->postJson(route('v1.change.password', ['id' => $user->id]), $data);

    $response->assertStatus(419);
    expect($response)->toBeFailed();
});

test("User can't change password becouse user id not found", function () {

    $user = User::factory()->create();

    $data = [
        "new_password" => "password_new",
        "old_password" => "password_valid",
    ];
    $response = $this->postJson(route('v1.change.password', ['id' => 100]), $data);

    $response->assertStatus(404);
    expect($response)->toBeFailed();
});
