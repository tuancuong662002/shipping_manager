<?php
namespace App\Services;

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
                'updated_at' => $order->updated_at->toIso8601String(),
            ];
            $order->webhook_url = 'http://localhost:8001/api/webhook';
            $response = Http::post($order->webhook_url, $payload);

            if ($response->failed()) {
                Log::error("Webhook failed for order {$order->order_id}: " . $response->body());
            } else {
                Log::info("Webhook sent successfully for order {$order->order_id}");
            }
        }
    }
}