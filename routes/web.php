<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/contacto', function () {
    return view('contact');
})->name('contact');

Route::get('/como-comprar', function () {
    return view('how-to-buy');
})->name('how-to-buy');

Route::get('/blog', function () {
    return view('blog');
})->name('blog');

Route::get('/tienda', [ShopController::class, 'index'])->name('shop');
Route::get('/tienda/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

Route::middleware(['auth'])->get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard');

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('products', ProductController::class)->except('show');
        Route::resource('users', UserController::class)->except('show');

        Route::delete('products/images/{image}', [ProductController::class, 'destroyImage'])
            ->name('products.images.destroy');
    });

require __DIR__.'/auth.php';