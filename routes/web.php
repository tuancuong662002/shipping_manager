<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::put('/orders/{orderId}/status', [OrderController::class, 'updateStatus'])->name('update.status');