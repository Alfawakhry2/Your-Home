<?php

use App\Http\Controllers\front\CartController;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\front\CheckoutController;
use App\Http\Controllers\Front\EstateController;
use App\Http\Controllers\front\ReservationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

#Category
Route::get('estate/category/{id}' , [CategoryController::class , 'categoryEstates'])->name('category.estates');

#Estate
Route::get('estates' , [EstateController::class , 'index'])->name('estates.index');
Route::get('estate/{id}' , [EstateController::class , 'show'])->name('estate.show');


#cart
Route::get('cart' , [CartController::class  , 'index'])->name('cart.index');
Route::post('cart' , [CartController::class  , 'store'])->name('cart.store');
Route::delete('cart/{id}/delete' , [CartController::class  , 'delete'])->name('cart.delete');
Route::delete('cart/empty' , [CartController::class  , 'empty'])->name('cart.empty');

#checkout
Route::get('checkout/estate/{id}' , [CheckoutController::class , 'checkout'])->name('checkout.estate');
Route::post('checkout/estate/{id}/pay' , [CheckoutController::class , 'pay'])->name('checkout.pay');


#reservation
Route::get('reservation' , [ReservationController::class , 'index'])->name('reservation.index');
Route::post('reservation/estate/{id}' , [ReservationController::class , 'store'])->name('reservation.store');

