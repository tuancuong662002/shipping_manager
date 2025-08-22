<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Management Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    .gradient-bg {
        background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 30%, #ddd6fe 70%, #fce7f3 100%);
    }

    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .modal {
        transition: all 0.3s ease;
        opacity: 0;
        visibility: hidden;
        transform: scale(0.95);
    }

    .modal-active {
        opacity: 1;
        visibility: visible;
        transform: scale(1);
    }

    .status-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    .glass-effect {
        backdrop-filter: blur(16px);
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        transform: translateY(-1px);
    }

    .table-row:hover {
        background: linear-gradient(90deg, rgba(59, 130, 246, 0.05) 0%, rgba(147, 51, 234, 0.05) 100%);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-in {
        animation: slideIn 0.5s ease-out;
    }
    </style>
</head>

<body class="min-h-screen gradient-bg">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Enhanced Header Section -->
        <div class="mb-10 animate-slide-in">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-2xl flex items-center justify-center shadow-xl">
                        <i class="fas fa-shipping-fast text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1
                            class="text-5xl font-black bg-gradient-to-r from-gray-900 via-blue-800 to-purple-800 bg-clip-text text-transparent">
                            Shipping Dashboard
                        </h1>
                        <p class="text-gray-600 text-lg font-medium mt-1">Advanced order management & tracking system
                        </p>
                    </div>
                </div>
                <div class="hidden md:flex items-center gap-3 glass-effect px-6 py-3 rounded-xl">
                    <i class="fas fa-clock text-blue-600"></i>
                    <span class="text-gray-700 font-medium">Last updated: {{ now()->format('H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Enhanced Alert Messages -->
        @if (session('success'))
        <div class="mb-8 glass-effect border-l-4 border-emerald-500 rounded-xl p-6 shadow-lg animate-slide-in">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-emerald-600"></i>
                </div>
                <div>
                    <h4 class="text-emerald-800 font-bold text-lg">Success!</h4>
                    <p class="text-emerald-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="mb-8 glass-effect border-l-4 border-red-500 rounded-xl p-6 shadow-lg animate-slide-in">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <h4 class="text-red-800 font-bold text-lg">Error!</h4>
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Enhanced Statistics Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="glass-effect rounded-2xl p-6 card-hover animate-slide-in">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-boxes text-white"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-black text-gray-800">{{ $orders->count() }}</p>
                        <p class="text-blue-600 font-semibold text-sm">+12% from last week</p>
                    </div>
                </div>
                <h3 class="text-gray-600 font-bold text-sm uppercase tracking-wider">Total Orders</h3>
                <div class="mt-3 bg-blue-100 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: 75%"></div>
                </div>
            </div>

            <div class="glass-effect rounded-2xl p-6 card-hover animate-slide-in" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-truck text-white"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-black text-gray-800">
                            {{ $orders->where('status', 'OutForDelivery')->count() }}</p>
                        <p class="text-teal-600 font-semibold text-sm">Active deliveries</p>
                    </div>
                </div>
                <h3 class="text-gray-600 font-bold text-sm uppercase tracking-wider">Out For Delivery</h3>
                <div class="mt-3 bg-teal-100 rounded-full h-2">
                    <div class="bg-gradient-to-r from-teal-500 to-teal-600 h-2 rounded-full status-pulse"
                        style="width: 60%"></div>
                </div>
            </div>

            <div class="glass-effect rounded-2xl p-6 card-hover animate-slide-in" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-black text-gray-800">
                            {{ $orders->where('status', 'Delivered')->count() }}</p>
                        <p class="text-emerald-600 font-semibold text-sm">Successfully delivered</p>
                    </div>
                </div>
                <h3 class="text-gray-600 font-bold text-sm uppercase tracking-wider">Delivered</h3>
                <div class="mt-3 bg-emerald-100 rounded-full h-2">
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2 rounded-full" style="width: 85%">
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-2xl p-6 card-hover animate-slide-in" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-circle text-white"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-black text-gray-800">
                            {{ $orders->whereIn('status', ['Failed', 'Cancelled'])->count() }}</p>
                        <p class="text-red-600 font-semibold text-sm">Needs attention</p>
                    </div>
                </div>
                <h3 class="text-gray-600 font-bold text-sm uppercase tracking-wider">Issues</h3>
                <div class="mt-3 bg-red-100 rounded-full h-2">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 h-2 rounded-full" style="width: 25%"></div>
                </div>
            </div>
        </div>

        <!-- Enhanced Orders Table -->
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden animate-slide-in" style="animation-delay: 0.4s">
            <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-blue-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-list-alt text-blue-600 text-xl"></i>
                        <h2 class="text-2xl font-black text-gray-800">Order Management</h2>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="glass-effect px-4 py-2 rounded-lg">
                            <span class="text-gray-600 font-medium">Total: </span>
                            <span class="font-black text-gray-800">{{ $orders->count() }}</span>
                        </div>
                        <button class="btn-primary text-white px-4 py-2 rounded-lg font-medium">
                            <i class="fas fa-download mr-2"></i>Export
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-100 to-blue-100 border-b-2 border-gray-200">
                            <th class="text-left py-5 px-8 font-black text-gray-700 text-sm uppercase tracking-wider">
                                <i class="fas fa-hashtag mr-2"></i>Order Details
                            </th>
                            <th class="text-left py-5 px-8 font-black text-gray-700 text-sm uppercase tracking-wider">
                                <i class="fas fa-user mr-2"></i>Customer Info
                            </th>
                            <th class="text-left py-5 px-8 font-black text-gray-700 text-sm uppercase tracking-wider">
                                <i class="fas fa-map-marker-alt mr-2"></i>Delivery Address
                            </th>
                            <th class="text-left py-5 px-8 font-black text-gray-700 text-sm uppercase tracking-wider">
                                <i class="fas fa-info-circle mr-2"></i>Status
                            </th>
                            <th class="text-left py-5 px-8 font-black text-gray-700 text-sm uppercase tracking-wider">
                                <i class="fas fa-cogs mr-2"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                        <tr class="table-row transition-all duration-300">
                            <td class="py-6 px-8">
                                <div class="space-y-2">
                                    <div class="font-black text-gray-900 text-lg">#{{ $order->order_id }}</div>
                                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>{{ $order->created_at->format('M d, Y • H:i') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">
                                            {{ $order->seller ?? 'Customer Name Not Available' }}
                                        </div>
                                        <div class="text-gray-500 text-sm">Customer ID: #{{ $order->id ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-start gap-2 text-gray-600 text-sm max-w-xs">
                                    <i class="fas fa-map-marker-alt text-gray-400 mt-1"></i>
                                    <span>{{ $order->address ?? 'Address Not Provided' }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                @php
                                $statusConfig = [
                                'Pending' => [
                                'class' => 'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 border-gray-300',
                                'icon' => 'fas fa-clock',
                                'label' => 'Chưa gửi'
                                ],
                                'OutForDelivery' => [
                                'class' => 'bg-gradient-to-r from-teal-100 to-teal-200 text-teal-800 border-teal-300',
                                'icon' => 'fas fa-truck',
                                'label' => 'Đang giao hàng'
                                ],
                                'Delivered' => [
                                'class' => 'bg-gradient-to-r from-emerald-100 to-emerald-200 text-emerald-800
                                border-emerald-300',
                                'icon' => 'fas fa-check-circle',
                                'label' => 'Đã giao hàng'
                                ],
                                'Failed' => [
                                'class' => 'bg-gradient-to-r from-red-100 to-red-200 text-red-800 border-red-300',
                                'icon' => 'fas fa-times-circle',
                                'label' => 'Giao hàng thất bại'
                                ],
                                'Cancelled' => [
                                'class' => 'bg-gradient-to-r from-red-100 to-red-200 text-red-800 border-red-300',
                                'icon' => 'fas fa-ban',
                                'label' => 'Đã hủy'
                                ],
                                ];
                                $config = $statusConfig[$order->status] ?? [
                                'class' => 'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 border-gray-300',
                                'icon' => 'fas fa-clock',
                                'label' => 'Chưa gửi'
                                ];
                                @endphp
                                <span
                                    class="inline-flex items-center gap-3 px-4 py-2 rounded-full text-sm font-bold border-2 {{ $config['class'] }} {{ $order->status === 'OutForDelivery' ? 'status-pulse' : '' }}">
                                    <i class="{{ $config['icon'] }}"></i>
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="py-6 px-8">
                                @php
                                $actionButtons = [
                                'Pending' => [
                                ['status' => 'OutForDelivery', 'label' => 'Out For Delivery', 'icon' => 'fas fa-truck',
                                'class' => 'bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600
                                hover:to-teal-700'],
                                ['status' => 'Cancelled', 'label' => 'Cancel', 'icon' => 'fas fa-ban', 'class' =>
                                'bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700']
                                ],
                                'OutForDelivery' => [
                                ['status' => 'Delivered', 'label' => 'Delivered', 'icon' => 'fas fa-check-circle',
                                'class' => 'bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600
                                hover:to-emerald-700'],
                                ['status' => 'Failed', 'label' => 'Failed', 'icon' => 'fas fa-times-circle', 'class' =>
                                'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700']
                                ],
                                'Failed' => [
                                ['status' => 'OutForDelivery', 'label' => 'Retry Delivery', 'icon' => 'fas fa-redo',
                                'class' => 'bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600
                                hover:to-teal-700']
                                ],
                                'Delivered' => [],
                                'Cancelled' => []
                                ];
                                $buttons = $actionButtons[$order->status] ?? [];
                                @endphp
                                @if (!empty($buttons))
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($buttons as $button)
                                    <button type="button"
                                        onclick="openModal('{{ e($order->order_id) }}', '{{ $button['status'] }}')"
                                        class="{{ $button['class'] }} text-white px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 shadow-lg hover:shadow-xl hover:transform hover:-translate-y-1">
                                        <i class="{{ $button['icon'] }} mr-2"></i>{{ $button['label'] }}
                                    </button>
                                    @endforeach
                                </div>
                                @else
                                <div class="flex items-center gap-2 text-gray-500">
                                    <i class="fas fa-check-circle"></i>
                                    <span class="text-sm font-medium">Complete</span>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-16 px-8 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-500 text-xl font-bold mb-2">No orders found</h3>
                                        <p class="text-gray-400">Orders will appear here once they are created</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Enhanced Status Update Modal -->
        <div id="statusModal"
            class="modal fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 backdrop-blur-sm">
            <div class="glass-effect rounded-3xl p-8 w-full max-w-2xl mx-4 shadow-2xl border border-white/20">
                <div class="flex items-center gap-4 mb-8">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-edit text-white"></i>
                    </div>
                    <h2 id="modalTitle" class="text-2xl font-black text-gray-800"></h2>
                </div>

                <form id="statusForm" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" id="modalStatus">
                    <input type="hidden" name="order_id" id="modalOrderId">

                    <!-- Delivered Status Fields -->
                    <div id="deliveredFields" class="hidden space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-camera text-blue-600"></i>
                                    Proof Image 1 (Required)
                                </label>
                                <input type="file" name="proof_image1" id="proofImage1" accept="image/*"
                                    class="border-2 border-gray-300 rounded-xl px-4 py-3 w-full focus:border-blue-500 focus:outline-none transition-colors">
                                <div id="thumbnail1" class="mt-4"></div>
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-camera text-blue-600"></i>
                                    Proof Image 2 (Required)
                                </label>
                                <input type="file" name="proof_image2" id="proofImage2" accept="image/*"
                                    class="border-2 border-gray-300 rounded-xl px-4 py-3 w-full focus:border-blue-500 focus:outline-none transition-colors">
                                <div id="thumbnail2" class="mt-4"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Reason Fields for Failed/Cancelled -->
                    <div id="reasonFields" class="hidden">
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-3">
                            <i class="fas fa-edit text-blue-600"></i>
                            Reason for Status Change
                        </label>
                        <textarea name="reason" id="reason"
                            class="border-2 border-gray-300 rounded-xl px-4 py-3 w-full focus:border-blue-500 focus:outline-none transition-colors resize-none"
                            rows="4"
                            placeholder="Please provide a detailed reason for this status change..."></textarea>
                        <div id="reasonError" class="hidden text-red-600 text-sm mt-2 flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            Reason is required for this status change.
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeModal()"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-xl font-bold transition-all duration-200 hover:transform hover:-translate-y-1">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </button>
                        <button type="submit" id="confirmButton"
                            class="btn-primary text-white px-6 py-3 rounded-xl font-bold transition-all duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:transform-none shadow-lg hover:shadow-xl"
                            disabled>
                            <i class="fas fa-check mr-2"></i>Confirm Update
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Enhanced Footer -->
        <div class="mt-16 text-center">
            <div class="glass-effect rounded-2xl p-6 inline-block">
                <p class="text-gray-600 font-medium">
                    © {{ date('Y') }} Shipping Management System •
                    <span class="text-blue-600 font-bold">Advanced Order Tracking</span>
                </p>
            </div>
        </div>
    </div>

    <script>
    // Enhanced configuration for valid status transitions
    const statusConfig = {
        'Pending': {
            allowedTransitions: ['OutForDelivery', 'Cancelled'],
            label: 'Chưa gửi',
            icon: 'fas fa-clock',
            class: 'bg-gray-100 text-gray-800 border-gray-300'
        },
        'OutForDelivery': {
            allowedTransitions: ['Delivered', 'Failed'],
            label: 'Đang giao hàng',
            icon: 'fas fa-truck',
            class: 'bg-teal-100 text-teal-800 border-teal-300'
        },
        'Failed': {
            allowedTransitions: ['OutForDelivery'],
            label: 'Giao hàng thất bại',
            icon: 'fas fa-times-circle',
            class: 'bg-red-100 text-red-800 border-red-300'
        },
        'Delivered': {
            allowedTransitions: [],
            label: 'Đã giao hàng',
            icon: 'fas fa-check-circle',
            class: 'bg-green-100 text-green-800 border-green-300'
        },
        'Cancelled': {
            allowedTransitions: [],
            label: 'Đã hủy',
            icon: 'fas fa-ban',
            class: 'bg-red-100 text-red-800 border-red-300'
        }
    };

    // Enhanced button configurations
    const actionButtons = {
        'OutForDelivery': {
            label: 'Out For Delivery',
            icon: 'fas fa-truck',
            class: 'bg-teal-600 hover:bg-teal-700'
        },
        'Delivered': {
            label: 'Delivered',
            icon: 'fas fa-check-circle',
            class: 'bg-green-600 hover:bg-green-700'
        },
        'Failed': {
            label: 'Failed',
            icon: 'fas fa-times-circle',
            class: 'bg-red-600 hover:bg-red-700'
        },
        'Cancelled': {
            label: 'Cancel',
            icon: 'fas fa-ban',
            class: 'bg-gray-600 hover:bg-gray-700'
        },
        'Retry Delivery': {
            label: 'Retry Delivery',
            icon: 'fas fa-redo',
            class: 'bg-teal-600 hover:bg-teal-700'
        }
    };

    function openModal(orderId, status) {
        try {
            console.log(`Opening modal for order ${orderId} with status ${status}`);

            const modal = document.getElementById('statusModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalStatus = document.getElementById('modalStatus');
            const modalOrderId = document.getElementById('modalOrderId');
            const deliveredFields = document.getElementById('deliveredFields');
            const reasonFields = document.getElementById('reasonFields');
            const reasonError = document.getElementById('reasonError');
            const confirmButton = document.getElementById('confirmButton');
            const proofImage1 = document.getElementById('proofImage1');
            const proofImage2 = document.getElementById('proofImage2');
            const thumbnail1 = document.getElementById('thumbnail1');
            const thumbnail2 = document.getElementById('thumbnail2');
            const reasonTextarea = document.getElementById('reason');

            // Reset form state
            deliveredFields.classList.add('hidden');
            reasonFields.classList.add('hidden');
            reasonError.classList.add('hidden');
            confirmButton.disabled = (status === 'Delivered');
            thumbnail1.innerHTML = '';
            thumbnail2.innerHTML = '';
            proofImage1.value = '';
            proofImage2.value = '';
            reasonTextarea.value = '';

            // Set modal content
            modalOrderId.value = orderId;
            modalStatus.value = status;
            modalTitle.textContent = `Update Status: ${actionButtons[status]?.label || status} for Order #${orderId}`;
            document.getElementById('statusForm').action = `/orders/${orderId}/status`;

            // Show appropriate fields based on status
            if (status === 'Delivered') {
                deliveredFields.classList.remove('hidden');
            } else if (status === 'Failed' || status === 'Cancelled') {
                reasonFields.classList.remove('hidden');
                confirmButton.disabled = true;
            } else {
                confirmButton.disabled = false;
            }

            // Show modal with enhanced animation
            modal.classList.add('modal-active');
            document.body.style.overflow = 'hidden';
        } catch (error) {
            console.error('Error in openModal:', error);
            alert('An error occurred while opening the modal. Please try again.');
        }
    }

    function closeModal() {
        try {
            const modal = document.getElementById('statusModal');
            modal.classList.remove('modal-active');
            document.body.style.overflow = 'auto';
        } catch (error) {
            console.error('Error in closeModal:', error);
        }
    }

    // Enhanced image preview and validation logic
    function setupImageListeners() {
        const proofImage1 = document.getElementById('proofImage1');
        const proofImage2 = document.getElementById('proofImage2');
        const thumbnail1 = document.getElementById('thumbnail1');
        const thumbnail2 = document.getElementById('thumbnail2');
        const confirmButton = document.getElementById('confirmButton');

        function checkDeliveredImages() {
            const hasImage1 = proofImage1.files && proofImage1.files[0];
            const hasImage2 = proofImage2.files && proofImage2.files[0];
            const modalStatus = document.getElementById('modalStatus').value;

            if (modalStatus === 'Delivered') {
                confirmButton.disabled = !(hasImage1 && hasImage2);
            }
        }

        function createImagePreview(file, container) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-32 object-cover rounded-xl border-2 border-gray-200 shadow-lg';

                const overlay = document.createElement('div');
                overlay.className =
                    'absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all duration-200 rounded-xl flex items-center justify-center';
                overlay.innerHTML =
                    '<i class="fas fa-eye text-white text-xl opacity-0 hover:opacity-100 transition-opacity"></i>';

                wrapper.appendChild(img);
                wrapper.appendChild(overlay);
                container.innerHTML = '';
                container.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        }

        proofImage1.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                createImagePreview(this.files[0], thumbnail1);
            } else {
                thumbnail1.innerHTML = '';
            }
            checkDeliveredImages();
        });

        proofImage2.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                createImagePreview(this.files[0], thumbnail2);
            } else {
                thumbnail2.innerHTML = '';
            }
            checkDeliveredImages();
        });

        // Enhanced reason validation for Failed/Cancelled
        const reasonTextarea = document.getElementById('reason');
        reasonTextarea.addEventListener('input', function() {
            const modalStatus = document.getElementById('modalStatus').value;
            const reasonError = document.getElementById('reasonError');
            if (modalStatus === 'Failed' || modalStatus === 'Cancelled') {
                const hasReason = this.value.trim().length > 0;
                confirmButton.disabled = !hasReason;
                reasonError.classList.toggle('hidden', hasReason);
            }
        });
    }

    // Enhanced modal close functionality
    document.getElementById('statusModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Enhanced keyboard support
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        setupImageListeners();
        console.log('Enhanced Shipping Management System initialized successfully');

        // Add smooth scrolling
        document.documentElement.style.scrollBehavior = 'smooth';
    });
    </script>
</body>

</html>