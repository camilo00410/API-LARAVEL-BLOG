<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\Auth\LoginController;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [LoginController::class, 'store']);


Route::post('register', [RegisterController::class, 'store'])->name('api.v1.register');

// Route::get('categories', [CategoryController::class, 'index'])->name('api.v1.categories.index');
// Route::post('categories', [CategoryController::class, 'store'])->name('api.v1.categories.store');
// Route::post('categories/{category}', [CategoryController::class, 'show'])->name('api.v1.categories.show');
// Route::put('categories/{category}', [CategoryController::class, 'update'])->name('api.v1.categories.udpate');
// Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('api.v1.categories.destroy');

Route::apiResource('categories', CategoryController::class)->names('api.v1.categories');
Route::apiResource('posts', PostController::class)->names('api.v1.posts');