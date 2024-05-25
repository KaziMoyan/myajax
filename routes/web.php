<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\ProductController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('products', [ProductController::class, 'index']);
Route::get('products/Edit/{id}/', [ProductController::class, 'edit']);
Route::post('products/Store', [ProductController::class, 'store']);
Route::get('products/Delete/{id}', [ProductController::class, 'destroy']);

