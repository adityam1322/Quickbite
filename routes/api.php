<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\RestaurantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;

Route::middleware('throttle:login')->group(function () {
    Route::post('/login', [
        AuthController::class,
        'login',
    ]);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [
        AuthController::class,
        'logout',
    ]);

    Route::post('/logout-all', [
        AuthController::class,
        'logoutAll',
    ]);

    Route::get('/me', [
        AuthController::class,
        'me',
    ]);

    Route::apiResource(
        'restaurants',
        RestaurantController::class
    );

    Route::apiResource(
        'menu-items',
        MenuItemController::class
    );

    Route::apiResource(
        'orders',
        OrderController::class
    );

    Route::apiResource(
        'deliveries',
        DeliveryController::class
    );
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    Route::get('/cart', [CartController::class, 'show']);

    Route::post('/cart/items', [CartController::class, 'addItem']);

    Route::patch(
        '/cart/items/{cartItem}',
        [CartController::class, 'updateItem']
    );

    Route::delete(
        '/cart/items/{cartItem}',
        [CartController::class, 'removeItem']
    );

    Route::delete(
        '/cart/items',
        [CartController::class, 'clear']
    );

    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::put('/orders/{order}', [OrderController::class, 'update']);
});
