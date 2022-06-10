<?php

use App\Http\Controllers\Api\V1\Auth\MobileAuthController;
use App\Http\Controllers\Api\V1\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::prefix("v1")->group(function () {
    Route::post("mobile/sign-up", [MobileAuthController::class, 'register']);
    Route::post("mobile/sign-in", [MobileAuthController::class, 'login']);

    Route::middleware("auth:sanctum")->group(function () {
        Route::get("mobile/check-token", [MobileAuthController::class, 'checkToken']);

        Route::get("store/main", [StoreController::class, 'userMainStore']);
        Route::apiResource('store', StoreController::class);
    });
});
