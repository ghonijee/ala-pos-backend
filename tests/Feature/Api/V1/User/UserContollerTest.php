<?php

use App\Models\User;

uses()->group('api', 'api.user');


beforeEach(function () {
    $user = User::factory()->create();

    actingAs($user);
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
