<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\NotFilamentUser;
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\front\CartController;
use App\Http\Controllers\Api\front\EstateController;
use App\Http\Controllers\Api\front\CategoryController;
use App\Http\Controllers\Api\front\CheckoutController;
use App\Http\Controllers\Api\filament\ReservationController;
use App\Http\Controllers\Api\front\PaymentDetailsController;
use App\Http\Controllers\Api\filament\EstateController as FilamentEstateController;
use App\Http\Controllers\Api\filament\CategoryController as FilamentCategoryController;
use App\Http\Controllers\Api\filament\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Auth

Route::prefix('auth')->controller(AuthController::class)->group(function(){
    Route::post('login' , 'login');
    Route::post('register' , 'register');
    Route::post('logout' , 'logout')->middleware('auth:sanctum');
});

## buyers
//tested in api
Route::controller(CategoryController::class)->group(function () {
    Route::get('categories', 'index');
});

//tested in api
Route::controller(EstateController::class)->group(function () {
    Route::get('estates', 'index');
});


Route::middleware(['auth:sanctum', 'role:buyer'])->group(function () {
    //tested in api
    Route::controller(CartController::class)->prefix('cart')->group(function () {
        Route::get('/', 'index');
        Route::post('/add', 'store');
        Route::delete('/remove/{id}', 'delete');
        Route::delete('/empty', 'empty');
    });

    Route::controller(CheckoutController::class)->group(function () {
        Route::post('/checkout/{estate}', 'reserve');
        Route::get('/pay/{reservation}', 'pay');
    });

    Route::get('/reservation/{id}/payment-details', [PaymentDetailsController::class, 'show']);
});

//out the auth , cause will come from paymob
Route::controller(CheckoutController::class)->group(function () {
    Route::get('/payment/response', 'paymentResponse');
    Route::post('/payment/callback', 'callback');
});

#### admins and sellers

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {

    //for User
    Route::apiResource('users', UserController::class)->middleware('role:admin');

    ## Category
    Route::controller(FilamentCategoryController::class)->group(function () {
        // Route::apiResource('categories' , CategoryController::class);
        ## OR (used custom route for more management)

        Route::get('categories', 'index')->middleware('role:admin,seller');
        Route::middleware('role:admin')->group(function () {
            Route::post('categories', 'store');
            Route::get('categories/{id}', 'show');
            Route::put('categories/{id}', 'update');
            Route::delete('categories/{id}', 'destroy');
        });
    });

    ## Estates
    Route::controller(FilamentEstateController::class)->middleware('role:admin,seller')->group(function () {
        // Route::apiResource('estates' , EstateController::class);
        ## OR (used custom route for more management)
        Route::get('estates', 'index');
        Route::post('estates', 'store');
        Route::get('estates/{id}', 'show');
        Route::put('estates/{id}', 'update');
        Route::delete('estates/{id}', 'destroy');
    });

    ##Reservations
    Route::controller(ReservationController::class)->middleware('role:admin,seller')->group(function () {
        Route::get('reservations', 'index');
    });
});
