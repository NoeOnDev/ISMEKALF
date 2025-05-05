<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductBatchController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

// Redireccionar la ruta principal al login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Todas las rutas protegidas por autenticación y estado activo del usuario
Route::middleware(['auth', 'active'])->group(function () {
    // Rutas de perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas exclusivas para administradores
    Route::middleware('role:administrador')->group(function () {
        // Gestión de usuarios
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        Route::put('/admin/users/{user}/block', [UserController::class, 'block'])->name('admin.users.block');
        Route::put('/admin/users/{user}/unblock', [UserController::class, 'unblock'])->name('admin.users.unblock');

        // Exportaciones (solo administradores)
        Route::post('/products/export', [ProductController::class, 'export'])->name('products.export');
        Route::post('/inventory/export', [ProductBatchController::class, 'exportAllBatches'])->name('inventory.export');
    });

    // Rutas para administradores y almacén
    Route::middleware('role:administrador|almacen')->group(function () {
        // Gestión de inventario
        Route::resource('products', ProductController::class);

        // Gestión de lotes
        Route::get('/products/{product}/batches', [ProductBatchController::class, 'index'])->name('products.batches.index');
        Route::get('/products/{product}/batches/create', [ProductBatchController::class, 'create'])->name('products.batches.create');
        Route::post('/products/{product}/batches', [ProductBatchController::class, 'store'])->name('products.batches.store');
        Route::delete('/product-batches/{batch}', [ProductBatchController::class, 'destroy'])->name('products.batches.destroy');
        Route::get('/batches/{batch}', [ProductBatchController::class, 'show'])->name('products.batches.show');

        // Inventario global
        Route::get('/inventory', [ProductBatchController::class, 'allBatches'])->name('inventory.index');

        // Gestión de clientes
        Route::resource('clients', ClientController::class);

        // Gestión de marcas y proveedores
        Route::resource('brands', BrandController::class);
        Route::resource('suppliers', SupplierController::class);

        // Gestión de remisiones (proceso de salida de productos)
        Route::get('/orders/create/step1', [OrderController::class, 'createStep1'])->name('orders.create.step1');
        Route::post('/orders/create/step2', [OrderController::class, 'storeStep1'])->name('orders.store.step1');
        Route::get('/orders/create/step2', [OrderController::class, 'createStep2'])->name('orders.create.step2');
        Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
        Route::resource('orders', OrderController::class)->except(['create', 'store']);
    });
});

require __DIR__ . '/auth.php';
