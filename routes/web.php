<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create'); // NUEVA RUTA
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store'); // NUEVA RUTA
        Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
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
