<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
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
    Route::post('products/store', [ProductsController::class, 'store'])->name('product.store');
    Route::get('products/edit/{id}', [ProductsController::class, 'edit'])->name('product.edit');
    Route::post('products/update', [ProductsController::class, 'update'])->name('product.update');
    Route::get('products/delete', [ProductsController::class, 'delete'])->name('product.delete');
    Route::get('products/stock/{id}', [ProductsController::class, 'updateStock'])->name('product.updateStock');

    Route::get('clients', [ClientController::class, 'index'])->name('client.index');
    Route::get('clients/create', [ClientController::class, 'create'])->name('client.create');
    Route::get('clients/edit/{client}', [ClientController::class, 'edit'])->name('client.edit');
    Route::get('clients/show/{client}', [ClientController::class, 'show'])->name('client.show');
    Route::post('clients/update', [ClientController::class, 'update'])->name('client.update');
    Route::post('clients/store', [ClientController::class, 'store'])->name('client.store');
    Route::delete('client/{client}', [ClientController::class, 'destroy'])->name('client.destroy');
    Route::get('clients/order/{client}', [ClientController::class, 'newOrder'])->name('client.new.order');
    Route::post('clients/order/attach/{client}', [ClientController::class, 'attachOrder'])->name('client.store.order');

    Route::get('order', [OrderController::class, 'index'])->name('order.index');
    Route::post('order/create', [OrderController::class, 'create'])->name('order.create');
    Route::get('order/edit/{order}', [OrderController::class, 'edit'])->name('order.edit');
    Route::post('order/update', [OrderController::class, 'update'])->name('order.update');
    Route::get('order/show/{order}', [OrderController::class, 'show'])->name('order.show');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('order.destroy');
    Route::get('order/change-status', [OrderController::class, 'changeStatus'])->name('order.changestatus');
});

require __DIR__.'/auth.php';
