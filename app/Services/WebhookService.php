<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    public function sendWebhook($order)
    {
        if ($order) {
            
            $response = Http::post('http://localhost:8001/api/webhook', $order);

            if ($response->failed()) {
                Log::error("Webhook sent fail for order : ", $order );
            } else {
                Log::info("Webhook sent successfully for order : ", $order );
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
                'status' => 'Pending',
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