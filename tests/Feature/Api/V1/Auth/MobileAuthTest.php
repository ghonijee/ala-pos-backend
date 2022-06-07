<?php

it("User can't register with invalid data", function () {
    $data = [
        "fullname" => "Ahmad Yunus Afghoni",
        "phone" => "nomer_hp",
        "password" => "password"
    ];
    $response = $this->post('api/v1/sign-up', $data);

    $response->assertStatus(402);
});
