<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductFileController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return Inertia::render('Landing');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth', 'role:Administrator')->group(function () {
    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::put('/roles/{role}', [RoleController::class, 'update']);
    Route::delete('/roles/{role}', [RoleController::class, 'destroy']);
});

Route::middleware(['auth', 'role:Administrator'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});

Route::middleware('auth')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    Route::get('/categories/{category}/audit', [CategoryController::class, 'audit']);
    Route::post('/categories/export', [CategoryController::class, 'export']);
    Route::post('/categories/import', [CategoryController::class, 'import']);
});

Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    Route::get('/products/{product}/audit', [ProductController::class, 'audit']);
    Route::post('/products/{id}/restore', [ProductController::class, 'restore']);
    Route::delete('/products/{id}/force', [ProductController::class, 'forceDelete']);
    Route::post('/products/export', [ProductController::class, 'export']);
    Route::post('/products/import', [ProductController::class, 'import']);
});

Route::middleware('auth')->group(function () {
    Route::post('/products/{product}/files', [ProductFileController::class, 'store']);
    Route::delete('/product-files/{file}', [ProductFileController::class, 'destroy']);
});

Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{order}', [OrderController::class, 'update']);
    Route::delete('/orders/{order}', [OrderController::class, 'destroy']);
    Route::get('/orders/{order}/audit', [OrderController::class, 'audit']);
    Route::post('/orders/{id}/restore', [OrderController::class, 'restore']);
    Route::delete('/orders/{id}/force', [OrderController::class, 'forceDelete']);
    Route::post('/orders/export', [OrderController::class, 'export']);
    Route::post('/orders/import', [OrderController::class, 'import']);
});


Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->middleware('auth');


