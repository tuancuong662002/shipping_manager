<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WebhookService;

class WebhookController extends Controller
{
    protected $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function handleSellerWebhook(Request $request)
    {
        // Gọi service để xử lý
         $payload = $request->all();   
        return $this->webhookService->handleSellerWebhook($payload);
    }
}