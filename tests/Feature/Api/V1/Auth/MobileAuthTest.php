<?php

use App\Models\User;

uses()->group('api', 'api.auth');

test("User can't register with invalid data", function ($fullname, $username, $phone, $password) {
    $data = [
        "fullname" => $fullname,
        "username" => $username,
        "phone" => $phone,
        "password" => $password
    ];
    $response = $this->post('api/v1/mobile/sign-up', $data);

    $response->assertStatus(422);
    expect($response)->toBeFailed();
})->with([
    ["Ahmad", "yunus", "nomer_hp", "password"],
    ["Ahmad", "yunus2", "081510897752", ""],
    ["Ahmad", "", "081510897752", "password"],
    ["Ahmad", "yunus", "081510897752", "password"],
]);

test("User can register without fullname", function () {
    $data = [
        "username" => "yunus2",
        "phone" => "081510897754",
        "password" => "password",
        "device_name" => "test"
    ];
    $response = $this->post('api/v1/mobile/sign-up', $data);

    $response->assertStatus(200);
    expect($response)->toBeSuccess();

    $user = User::where("username", "yunus2")->first();
    expect($user)->toBeObject();
    expect($user->fullname)->toEqual("yunus2");
});

test("User can't login with invalid request data", function ($username, $password, $device) {
    $data = [
        "username" => $username,
        "password" => $password,
        "device_name" => $device,
    ];

    $response = $this->post('api/v1/mobile/sign-in', $data);
    $response->assertStatus(422);
    expect($response)->toBeFailed();
})->with([
    ["yunus", "", null],
    ["yunus", "password", null],
    ["", "password", "mobile"]
]);

test("User can't login with incorrect credentials", function ($username, $password, $device) {
    $data = [
        "username" => $username,
        "password" => $password,
        "device_name" => $device,
    ];

    $response = $this->post('api/v1/mobile/sign-in', $data);
    $response->assertStatus(401);
    expect($response)->toBeFailed();
})->with([
    ["yunus", "password1", "pest"],
    ["yunus1", "password", "wrong"],
    ["password", "pest", "mobile"]
]);


test("User can login with correct credentials", function () {
    $store = User::create([
        "username" => "yunus",
        "phone" => "081510897753",
        "password" => "password",
        "device_name" => "test"
    ]);

    $data = [
        "username" => "yunus",
        "password" => "password",
        "device_name" => "pest",
    ];

    $response = $this->post('api/v1/mobile/sign-in', $data);

    $response->assertStatus(200);
    expect($response)->toBeSuccess();

    $body = $response->getData();
    expect($body->data)->toHaveProperty("personal_access_token");
    expect($body->data)->toHaveProperty("user");
});

test("User can check auth status or token verification", function () {
    $user = User::factory()->create();
    actingAs($user);
    $response =  $this->getJson("api/v1/mobile/check-token");
    expect($response)->toBeSuccess();
    $body = $response->getData();
    expect($body)->message->toEqual("Token is valid");
    expect($body)->data->username->toEqual($user->username);
});

test("User check auth status or token verification", function () {
    $user = User::factory()->create();
    $response = $this->getJson("api/v1/mobile/check-token");
    $body = $response->getData();
    expect($body)->message->toEqual("Unauthenticated.");
});

test("User can logout", function () {
    $user = User::factory()->create();
    $auth = actingAs($user);
    $response = $this->getJson("api/v1/mobile/logout");
    $body = $response->getData();
    expect($body)->message->toEqual("Logout success");
});
