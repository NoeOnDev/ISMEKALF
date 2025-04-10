<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para administradores
    Route::middleware('role:administrador')->group(function () {
        Route::get('/admin/users', function () {
            return 'Gestión de usuarios - Solo administradores';
        })->name('admin.users');
    });

    // Rutas para almacén
    Route::middleware('role:administrador|almacen')->group(function () {
        Route::get('/inventory', function () {
            return 'Gestión de inventario - Administradores y Almacén';
        })->name('inventory');

        Route::resource('products', ProductController::class);
    });
});

require __DIR__ . '/auth.php';
