<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\RestaurantController;
use Illuminate\Support\Facades\Route;

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