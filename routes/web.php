<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('user', [UserController::class, 'index'])->name('user.index');
    Route::get('user/new', [UserController::class, 'create'])->name('user.create');
    Route::post('user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('user/update', [UserController::class, 'update'])->name('user.update');
    Route::delete('user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');

    Route::get('products', [ProductsController::class, 'index'])->name('product.index');
    Route::get('products/new', [ProductsController::class, 'create'])->name('product.create');
    Route::get('products/edit', [ProductsController::class, 'edit'])->name('product.edit');
    Route::get('products/delete', [ProductsController::class, 'delete'])->name('product.delete');

    Route::get('clients', [ClientController::class, 'index'])->name('client.index');
    Route::get('clients/create', [ClientController::class, 'create'])->name('client.create');
    Route::get('clients/edit', [ClientController::class, 'edit'])->name('client.edit');
    Route::get('clients/delete', [ClientController::class, 'create'])->name('client.delete');

    Route::get('order', [OrderController::class, 'index'])->name('order.index');
    Route::get('order/create', [OrderController::class, 'create'])->name('order.create');
    Route::get('order/edit', [OrderController::class, 'edit'])->name('order.edit');
    Route::get('order/delete', [OrderController::class, 'create'])->name('order.delete');
});

require __DIR__.'/auth.php';
