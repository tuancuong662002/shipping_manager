<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    public function sendWebhook($order)
    {
        if ($order->webhook_url) {
            $payload = [
                'order_id' => $order->order_id,
                'status' => $order->status,
                'updated_at' => $order->updated_at ? $order->updated_at->toIso8601String() : now()->toIso8601String(),
                'proof_image1' => $order->proof_image1 ? asset('storage/' . $order->proof_image1) : null,
                'proof_image2' => $order->proof_image2 ? asset('storage/' . $order->proof_image2) : null,
                'reason' => $order->reason ?? null,
                'tracking_number' => $order->tracking_number ?? null,
                'carrier' => $order->carrier ?? null,
                'delivery_date' => $order->delivery_date ? $order->delivery_date->toIso8601String() : null,
                'notes' => $order->notes ?? null,
            ];

            $response = Http::post($order->webhook_url, $payload);

            if ($response->failed()) {
                Log::error("Webhook failed for order {$order->order_id}: " . $response->status() . ' - ' . $response->body(), ['payload' => $payload]);
            } else {
                Log::info("Webhook sent successfully for order {$order->order_id}", ['status' => $response->status(), 'payload' => $payload]);
            }
        }
    }

    public function handleSellerWebhook(array $payload)
    {
        Log::info('Received webhook from Seller:', $payload);

        // Validate payload
        $validated = validator($payload, [
            'order_code' => 'required|string',
            'seller' => 'required|string',
            'seller_phone' => 'required|string',
            'shipping_address' => 'required|string',
        ])->validate(); 

        // Tạo hoặc cập nhật đơn hàng trong Shipping Manager
        $order = Order::updateOrCreate(
            ['order_id' => $validated['order_code']],
            [
                'status' => 'Shipped',
                'seller' => $validated['seller'],
                'sellerPhone' => $validated['seller_phone'],
                'address' => $validated['shipping_address'],
                'webhook_url' =>  'http://localhost:8001/api/webhook', // Cấu hình ngược lại nếu cần
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        Log::info("Order {$validated['order_code']} created/updated with status Shipped");

        return response()->json(['message' => 'Order received successfully'], 200);
    }
}