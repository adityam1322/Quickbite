<?php

use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentWebhookController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\Order\AcceptOrderController;
use App\Http\Controllers\Api\Order\CancelOrderController;
use App\Http\Controllers\Api\Order\DeliverOrderController;
use App\Http\Controllers\Api\Order\MarkOrderReadyController;
use App\Http\Controllers\Api\Order\PickupOrderController;
use App\Http\Controllers\Api\Order\RefundOrderController;
use App\Http\Controllers\Api\Order\RejectOrderController;
use App\Http\Controllers\Api\Order\StartPreparingOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderExportController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\Admin\RestaurantController as AdminRestaurantController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\RefundController as AdminRefundController;
use App\Http\Controllers\Api\Admin\ConfigurationController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\FoodDiscoveryController;

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('throttle:login')->group(function () {
    Route::post('/login', [
        AuthController::class,
        'login',
    ]);
});

/*
|--------------------------------------------------------------------------
| Authenticated API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);

    // Restaurants
    Route::apiResource(
        'restaurants',
        RestaurantController::class
    );
    
    //ai agent api
    Route::post('/ai/food-discovery', [
        FoodDiscoveryController::class,
        'discover',
    ]);

    // Menu items
    Route::apiResource(
        'menu-items',
        MenuItemController::class
    );

    // Standard order CRUD
    Route::apiResource(
        'orders',
        OrderController::class
    );

    // Explicit order status commands
    Route::post(
        '/orders/{order}/accept',
        AcceptOrderController::class
    );

    Route::post(
        '/orders/{order}/reject',
        RejectOrderController::class
    );

    Route::post(
        '/orders/{order}/start-preparing',
        StartPreparingOrderController::class
    );

    Route::post(
        '/orders/{order}/mark-ready',
        MarkOrderReadyController::class
    );

    Route::post(
        '/orders/{order}/pickup',
        PickupOrderController::class
    );

    Route::post(
        '/orders/{order}/deliver',
        DeliverOrderController::class
    );

    Route::post(
        '/orders/{order}/cancel',
        CancelOrderController::class
    );

    Route::post(
        '/orders/{order}/refund',
        RefundOrderController::class
    );

    // Deliveries
    Route::apiResource(
        'deliveries',
        DeliveryController::class
    );

    //delivery Availability

    Route::patch(
        '/delivery/availability',
        [DeliveryController::class, 'updateAvailability']
    );


    Route::prefix('admin')
        ->middleware('role:administrator')
        ->group(function () {
            // Users
            Route::get('/users', [UserController::class, 'index']);
            Route::get('/users/{user}', [UserController::class, 'show']);

            // Restaurants
            Route::get('/restaurants', [
                AdminRestaurantController::class,
                'index'
            ]);

            Route::get('/restaurants/{restaurant}', [
                AdminRestaurantController::class,
                'show'
            ]);

            Route::patch('/restaurants/{restaurant}/status', [
                AdminRestaurantController::class,
                'updateStatus'
            ]);

            Route::get('/orders', [
                AdminOrderController::class,
                'index',
            ]);

            Route::get('/orders/{order}', [
                AdminOrderController::class,
                'show',
            ]);

            Route::get('/refunds', [
                AdminRefundController::class,
                'index',
            ]);

            Route::get('/refunds/{refund}', [
                AdminRefundController::class,
                'show',
            ]);

            Route::get('/configurations', [
                ConfigurationController::class,
                'index',
            ]);

            Route::patch('/configurations/{configuration}', [
                ConfigurationController::class,
                'update',
            ]);

            // Order Export
            Route::post('/orders/export', [
                OrderExportController::class,
                'export',
            ]);

            Route::get('/dashboard', [
                DashboardController::class,
                'index',
            ]);
        });
});

/*
|--------------------------------------------------------------------------
| Version 1 API - Cart
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')
    ->prefix('v1')
    ->group(function () {

        Route::get('/cart', [
            CartController::class,
            'show',
        ]);

        Route::post('/cart/items', [
            CartController::class,
            'addItem',
        ]);

        Route::patch('/cart/items/{cartItem}', [
            CartController::class,
            'updateItem',
        ]);

        Route::delete('/cart/items/{cartItem}', [
            CartController::class,
            'removeItem',
        ]);

        Route::delete('/cart/items', [
            CartController::class,
            'clear',
        ]);
    });

/*
|--------------------------------------------------------------------------
| Payment Webhook
|--------------------------------------------------------------------------
|
| This route intentionally does not use auth:sanctum.
| The controller validates the HMAC signature and timestamp.
|
*/

Route::post(
    '/v1/payment/webhook',
    PaymentWebhookController::class
);

Route::middleware('auth:sanctum')->post('/orders/export', [OrderExportController::class, 'export']);


Route::post('/orders/{order}/rating', [RatingController::class, 'store']);
