<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::put('/orders/{orderId}/status', [OrderController::class, 'updateStatus'])->name('update.status');
Route::post('/api/webhook', [WebhookController::class, 'handleSellerWebhook']);