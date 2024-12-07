<?php

use App\Exports\ProductExport;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

// Grouping routes that require authentication
Route::middleware('auth')->group(function () {

    // Category Routes
    Route::post('/category/add', [CategoryController::class, 'store'])->name('category.store');

    // Export Product Data (Excel)
    Route::get('/product/export', [ProductController::class, 'export'])->name('product.export');


    // Product Routes (Using Controller)
    Route::controller(ProductController::class)->group(function () {
        // Show list of products
        Route::get('/product', 'index')->name('product');

        // Create new product
        Route::get('/product/add', 'create')->name('addproduct');
        Route::post('/product/add', 'store')->name('produk.store');

        // Edit product details
        Route::get('/product/edit/{id}', 'edit')->name('produk.edit');
        Route::post('/product/update/{id}', 'update')->name('product.update');

        // Delete product
        Route::post('/product/delete', 'delete')->name('product.delete');
    });

    // Profile Route
    Route::get('/profile', function () {
        return view('assignment.dashboard.profile');
    })->name('profile');
});

// Include authentication routes (login, register, etc.)
require __DIR__ . '/auth.php';
