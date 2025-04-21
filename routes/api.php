<?php

use Illuminate\Support\Facades\Route;

Route::post('/brands', [App\Http\Controllers\API\BrandController::class, 'store']);
Route::post('/suppliers', [App\Http\Controllers\API\SupplierController::class, 'store']);
Route::post('/clients', [App\Http\Controllers\API\ClientController::class, 'store']);
