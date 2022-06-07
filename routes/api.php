<?php

use App\Http\Controllers\Api\V1\Auth\MobileAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get("status", function () {
    return response()->json([
        "status" => true,
        "message" => "Rest Full API - Ala POS Active"
    ]);
});

Route::group(["prefix" => "v1"], function () {
    Route::post("mobile/sign-up", [MobileAuthController::class, 'register']);
    Route::post("mobile/sign-in", [MobileAuthController::class, 'login']);
});
