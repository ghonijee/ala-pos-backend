<?php

use App\Models\User;

uses()->group('api', 'api.user');


beforeEach(function () {
    $user = User::factory()->create();

    actingAs($user);
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
