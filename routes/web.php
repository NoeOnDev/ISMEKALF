<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Redireccionar la ruta principal al login
Route::get('/', function () {
    return redirect()->route('login');
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

    // Rutas para clientes y remisiones
    Route::middleware('role:administrador|almacen')->group(function () {
        // Gestión de clientes
        Route::resource('clients', ClientController::class);

        // Gestión de remisiones (proceso de salida de productos)
        Route::get('/orders/create/step1', [OrderController::class, 'createStep1'])->name('orders.create.step1');
        Route::post('/orders/create/step2', [OrderController::class, 'storeStep1'])->name('orders.store.step1');
        Route::get('/orders/create/step2', [OrderController::class, 'createStep2'])->name('orders.create.step2');
        Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
        Route::resource('orders', OrderController::class)->except(['create', 'store']);
    });
});

// Dentro del grupo de rutas que requiere autenticación
Route::middleware(['auth', 'role:administrador'])->group(function () {
    Route::post('/products/export', [ProductController::class, 'export'])->name('products.export');
});

require __DIR__ . '/auth.php';
