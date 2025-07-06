<?php

use App\Filament\Pages\Auth\PasswordReset\RequestPasswordReset;
use App\Filament\Pages\Auth\PasswordReset\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\RedirectIfNotBuyer;
use App\Http\Controllers\front\CartController;
use App\Http\Controllers\Front\EstateController;
use App\Http\Controllers\front\PaymobController;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\front\CheckoutController;
use App\Http\Controllers\front\ReservationController;
use App\Http\Middleware\RedirectIfFilamentUser;

Auth::routes(['verify'=>true]);

// RedirectIfFilamentUser can allow to guest or buyer , and only admin , seller can not access to these page
Route::middleware(RedirectIfFilamentUser::class)->group(function () {
    // بعدين: مسارات عامة (public) بدون أي redirect
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('estate/category/{id}', [CategoryController::class, 'categoryEstates'])->name('category.estates');
    Route::get('estates', [EstateController::class, 'index'])->name('estates.index');
    Route::get('estate/{id}', [EstateController::class, 'show'])->name('estate.show');
});

//should auth , and should buyer , else , will redirect to login
Route::middleware([RedirectIfNotBuyer::class , 'auth' , 'verified'])
    ->group(function () {
        Route::get('cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('cart', [CartController::class, 'store'])->name('cart.store');
        Route::delete('cart/{id}/delete', [CartController::class, 'delete'])->name('cart.delete');
        Route::delete('cart/empty', [CartController::class, 'empty'])->name('cart.empty');

        Route::get('checkout/estate/{id}', [CheckoutController::class, 'checkout'])->name('checkout.estate');
        Route::post('checkout/estate/{id}/pay', [CheckoutController::class, 'store'])->name('checkout.pay');

        Route::get('reservation', [ReservationController::class, 'index'])->name('reservation.index');
        Route::post('reservation/estate/{id}', [ReservationController::class, 'store'])->name('reservation.store');

        // Callback من Paymob لازم يكون مفتوح
        Route::post('/paymob/callback', [PaymobController::class, 'callback'])->name('paymob.callback');
        Route::get('/pay/estate/{estate}', [PaymobController::class, 'payEstate'])->name('pay.estate');
    });


