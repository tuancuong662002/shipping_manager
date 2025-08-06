<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderController;

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::put('/orders/{orderId}/update', [OrderController::class, 'updateStatus'])->name('update.status');