<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\WebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        Log::info("Updating status for order id: {$orderId}");

        $order = Order::where('order_id', $orderId)->firstOrFail();
        $oldStatus = $order->status; // Lấy status cũ trước khi update
        $newStatus = $request->input('status');
        $reason = $request->input('reason') ?? null;
        $updateData = ['status' => $newStatus];
        $img1 = null;
        $img2 = null;

        if ($newStatus === 'Delivered') {
            $request->validate([
                'proof_image1' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'proof_image2' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);
            
            $img1 = $request->file('proof_image1')->store('proofs', 'public');
            $img2 = $request->file('proof_image2')->store('proofs', 'public');

            $updateData['proof_image1'] = $img1;
            $updateData['proof_image2'] = $img2;
            
        }
        if( $newStatus == 'CancelledByUser'){
            $request->validate([
                'reason' => 'required',
            ]);
        }

        // Cập nhật
        $order->update($updateData);

        // Refresh dữ liệu từ DB để đảm bảo lấy status mới
        $order->refresh();

        if ($oldStatus !== $order->status) {
            $payload = [
                'orderId' => $orderId,
                'status' => $order->status,
                'deliver_date' => $order->updated_at, 
                'proof_image1' => $img1 ?? null,
                'proof_image2' => $img2 ?? null,
                'reason' => $reason ?? null
            ];
        
            $this->webhookService->sendWebhook($payload);
        } else {
            Log::info("Status for order {$orderId} was not changed. No webhook sent.");
        }

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}