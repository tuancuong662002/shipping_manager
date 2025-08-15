<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    public function sendWebhook(array $order)
    {
        if ($order) {
            $payload = [
                'order_id' => $order['orderId'] ?? null,
                'status' => $order['status'] ?? null,
                'proof_image1' => !empty($order['proof_image1']) ? asset('storage/' . $order['proof_image1']) : null,
                'proof_image2' => !empty($order['proof_image2']) ? asset('storage/' . $order['proof_image2']) : null,
                'reason' => $order['reason'] ?? null,
            ];

            $webhookUrl = $order['webhook_url'] ?? 'http://localhost:8001/api/webhook';

            $response = Http::post($webhookUrl, $payload);

            if ($response->failed()) {
                Log::error("Webhook failed for order {$payload['order_id']}: " . $response->status() . ' - ' . $response->body(), ['payload' => $payload]);
            } else {
                Log::info("Webhook sent successfully for order {$payload['order_id']}", ['status' => $response->status(), 'payload' => $payload]);
            }
        }
    }
}