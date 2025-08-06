<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\WebhookService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function index()
    {
       $orders = Order::orderBy('order_id', 'desc')->get();
        return view('orders', compact('orders'));
    }

    public function updateStatus(Request $request, $orderId)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        $newStatus = $request->input('status');

        if ($order->status !== $newStatus) {
            $order->update(['status' => $newStatus]);
            $this->webhookService->sendWebhook($order);
        }

        return redirect()->back()->with('success', 'Status updated and webhook sent.');
    }
}