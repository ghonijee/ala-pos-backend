<?php

use App\Http\Controllers\Api\V1\Auth\MobileAuthController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\StoreController;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\User\RoleController;
use App\Http\Controllers\Api\V1\User\UserController;
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

Route::prefix("v1")->name("v1.")->group(function () {
    Route::post("mobile/sign-up", [MobileAuthController::class, 'register']);
    Route::post("mobile/sign-in", [MobileAuthController::class, 'login']);

    Route::middleware("auth:sanctum")->group(function () {
        Route::post("change-password/{id}", [UserController::class, 'changePassword'])->name("change.password");

        Route::get("user/staff/{store_id}", [UserController::class, 'userStaff'])->name("role.userStaff");
        Route::apiResource('user', UserController::class)->only(['update', 'show', 'store']);

        Route::get("role/user/{id}", [RoleController::class, 'userRole'])->name("role.userRole");
        Route::apiResource("role", RoleController::class);


        Route::get("mobile/logout", [MobileAuthController::class, 'logout']);
        Route::get("mobile/check-token", [MobileAuthController::class, 'checkToken']);

        Route::get("store/main", [StoreController::class, 'userMainStore'])->name('store.main');
        Route::apiResource('store', StoreController::class);

        Route::get('product/find/{code}', [ProductController::class, 'findCode'])->name('product.code');
        Route::apiResource('product', ProductController::class);

        // Transaction
        Route::get('transaction/list/group-date', [TransactionController::class, 'listGroup'])->name('transaction.list.group');
        Route::apiResource('transaction', TransactionController::class)->only(['index', 'store']);
        // Route::post('transaction', [TransactionController::class, 'store'])->name('transaction.store');
    });
});
