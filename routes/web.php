<?php

use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\EstateController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

#Category
Route::get('estate/category/{id}' , [CategoryController::class , 'categoryEstates'])->name('category.estates');

#Estate
Route::get('estates' , [EstateController::class , 'index'])->name('estates.index');
Route::get('estate/{id}' , [EstateController::class , 'show'])->name('estate.show');


