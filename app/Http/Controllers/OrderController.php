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
        $orders = Order::orderBy('created_at', 'desc')->get();
        return view('orders', compact('orders'));
    }

   public function updateStatus(Request $request, $orderId)
    {
        Log::info("Updating status for order id: {$orderId}");

        // Find the order by order_id
        $order = Order::where('order_id', $orderId)->firstOrFail();
        $oldStatus = $order->status;
        $newStatus = $request->input('status');
        $reason = $request->input('reason') ?? null;

        // Define allowed transitions
        $allowedTransitions = [
            'Pending' => ['OutForDelivery', 'Cancelled'],
            'OutForDelivery' => ['Delivered', 'Failed'],
            'Failed' => ['OutForDelivery'],
            'Delivered' => [],
            'Cancelled' => []
        ];

        // 1. Check if the transition is allowed
        if (!isset($allowedTransitions[$oldStatus]) || !in_array($newStatus, $allowedTransitions[$oldStatus])) {
            Log::warning("Invalid status transition attempted: {$oldStatus} to {$newStatus} for order {$orderId}");
            return redirect()->back()->withErrors([
                'status' => "Không thể chuyển từ trạng thái {$oldStatus} sang {$newStatus}."
            ]);
        }

        // 2. Validate based on the new status
        $img1 = null;
        $img2 = null;
        $url1 = null;
        $url2 = null;

        if ($newStatus === 'Delivered') {
            $request->validate([
                'proof_image1' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'proof_image2' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $img1 = $request->file('proof_image1')->store('proofs', 'public');
            $img2 = $request->file('proof_image2')->store('proofs', 'public');
            $url1 = asset('storage/' . $img1);
            $url2 = asset('storage/' . $img2);
        }

        if (in_array($newStatus, ['Failed', 'Cancelled'])) {
            $request->validate([
                'reason' => 'required|string|max:255',
            ]);
        }

        // 3. Update the order
        $order->update([
            'status' => $newStatus,
            'reason' => $reason,
            'proof_image1' => $img1,
            'proof_image2' => $img2,
            'updated_at' => now(),
        ]);

        $order->refresh();

        // 4. Send webhook if status changed
        if ($oldStatus !== $order->status) {
            $payload = [
                'orderId' => $orderId,
                'status' => $order->status,
                'deliver_date' => $order->updated_at,
                'proof_image1' => $url1,
                'proof_image2' => $url2,
                'reason' => $reason,
            ];

            try {
                $this->webhookService->sendWebhook($payload);
                Log::info("Webhook sent for order {$orderId} with status {$newStatus}");
            } catch (\Exception $e) {
                Log::error("Failed to send webhook for order {$orderId}: {$e->getMessage()}");
            }
        } else {
            Log::info("Status for order {$orderId} was not changed. No webhook sent.");
        }

        return redirect()->back()->with('success', "Order status updated successfully from {$oldStatus} to {$newStatus}.");
    }
}