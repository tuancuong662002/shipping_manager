<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    .gradient-bg {
        background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 50%, #e0e7ff 100%);
    }

    .modal {
        transition: opacity 0.25s ease, visibility 0.25s ease;
        opacity: 0;
        visibility: hidden;
        display: flex;
    }

    .modal-active {
        opacity: 1;
        visibility: visible;
    }

    .status-badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.8;
        }
    }
    </style>
</head>

<body class="min-h-screen gradient-bg">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div
                    class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-white text-2xl">🚚</span>
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                    Shipping Management
                </h1>
            </div>
            <p class="text-gray-600 text-lg">Manage and track all your shipping orders efficiently</p>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
        <div
            class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 rounded-xl p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="text-green-500 text-xl">✨</span>
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-400 rounded-xl p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="text-red-500 text-xl">⚠️</span>
                <span class="text-red-800 font-medium">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Statistics Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div
                class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Total Orders</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $orders->count() }}</p>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                        <span class="text-blue-600 text-2xl">📦</span>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Out For Delivery</p>
                        <p class="text-3xl font-bold text-teal-600 mt-1">
                            {{ $orders->where('status', 'OutForDelivery')->count() }}
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-teal-100 rounded-xl flex items-center justify-center">
                        <span class="text-teal-600 text-2xl">🚚</span>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Delivered</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">
                            {{ $orders->where('status', 'Delivered')->count() }}
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                        <span class="text-green-600 text-2xl">✅</span>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Issues</p>
                        <p class="text-3xl font-bold text-red-600 mt-1">
                            {{ $orders->whereIn('status', ['DeliveryFailed', 'CancelledByBuyer'])->count() }}
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center">
                        <span class="text-red-600 text-2xl">❌</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-800">Recent Orders</h2>
                    <div class="text-sm text-gray-600">
                        Total: <span class="font-semibold">{{ $orders->count() }}</span> orders
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="text-left py-4 px-6 font-bold text-gray-700 text-sm uppercase tracking-wider">
                                Order Details
                            </th>
                            <th class="text-left py-4 px-6 font-bold text-gray-700 text-sm uppercase tracking-wider">
                                Customer Info
                            </th>
                            <th class="text-left py-4 px-6 font-bold text-gray-700 text-sm uppercase tracking-wider">
                                Delivery Address
                            </th>
                            <th class="text-left py-4 px-6 font-bold text-gray-700 text-sm uppercase tracking-wider">
                                Status
                            </th>
                            <th class="text-left py-4 px-6 font-bold text-gray-700 text-sm uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-6 px-6">
                                <div class="space-y-1">
                                    <div class="font-bold text-gray-900 text-lg">{{ $order->order_id }}</div>
                                    <!-- <div class="text-gray-600 font-medium">{{ $order->seller }}</div> -->
                                    <div class="text-gray-400 text-sm">
                                        📅 {{ $order->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-6">
                                <div class="font-semibold text-gray-900">
                                    {{ $order->seller ?? 'Customer Name Not Available' }}
                                </div>
                            </td>
                            <td class="py-6 px-6">
                                <div class="text-gray-600 text-sm max-w-xs">
                                    {{ $order->address ?? 'Address Not Provided' }}
                                </div>
                            </td>
                            <td class="py-6 px-6">
                                @php
                                $statusConfig = [
                                'OutForDelivery' => [
                                'class' => 'bg-teal-100 text-teal-800 border-teal-300',
                                'icon' => '🚚',
                                'label' => 'Đang giao hàng'
                                ],
                                'Delivered' => [
                                'class' => 'bg-green-100 text-green-800 border-green-300',
                                'icon' => '✅',
                                'label' => 'Đã giao hàng'
                                ],
                                'DeliveryFailed' => [
                                'class' => 'bg-red-100 text-red-800 border-red-300',
                                'icon' => '❌',
                                'label' => 'Giao hàng thất bại'
                                ],
                                'CancelledByBuyer' => [
                                'class' => 'bg-red-100 text-red-800 border-red-300',
                                'icon' => '🚫',
                                'label' => 'Hủy'
                                ],
                                ];
                                $config = $statusConfig[$order->status] ?? [
                                'class' => 'bg-gray-100 text-gray-800 border-gray-300',
                                'icon' => '📦',
                                'label' => 'Chưa gửi'
                                ];
                                @endphp
                                <span
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold border-2 status-badge {{ $config['class'] }}">
                                    <span class="text-lg">{{ $config['icon'] }}</span>
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="py-6 px-6">
                                @if (!in_array($order->status, ['Delivered', 'CancelledByBuyer']))
                                <div class="flex flex-wrap gap-2">
                                    <button type="button"
                                        onclick="openModal('{{ e($order->order_id) }}', 'OutForDelivery')"
                                        class="bg-teal-600 hover:bg-teal-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 shadow-sm">
                                        🚚 Out For Delivery
                                    </button>
                                    <button type="button" onclick="openModal('{{ e($order->order_id) }}', 'Delivered')"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 shadow-sm">
                                        ✅ Delivered
                                    </button>
                                    <button type="button"
                                        onclick="openModal('{{ e($order->order_id) }}', 'DeliveryFailed')"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 shadow-sm">
                                        ❌ Failed
                                    </button>
                                    <button type="button"
                                        onclick="openModal('{{ e($order->order_id) }}', 'CancelledByBuyer')"
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 shadow-sm">
                                        🚫 Cancel
                                    </button>
                                </div>
                                @else
                                <span class="text-gray-500 text-sm font-medium">No actions available</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center">
                                <div class="text-gray-400 text-6xl mb-4">📦</div>
                                <div class="text-gray-500 text-lg font-medium">No orders found</div>
                                <div class="text-gray-400 text-sm">Orders will appear here once they are created</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Status Update Modal -->
        <div id="statusModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-8 w-full max-w-lg mx-4 shadow-2xl">
                <h2 id="modalTitle" class="text-2xl font-bold mb-6 text-gray-800"></h2>

                <form id="statusForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" id="modalStatus">
                    <input type="hidden" name="order_id" id="modalOrderId">

                    <!-- Delivered Status Fields -->
                    <div id="deliveredFields" class="hidden space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                📸 Proof Image 1 (Required)
                            </label>
                            <input type="file" name="proof_image1" id="proofImage1" accept="image/*"
                                class="border-2 border-gray-300 rounded-lg px-4 py-3 w-full focus:border-blue-500 focus:outline-none">
                            <div id="thumbnail1" class="mt-3"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                📸 Proof Image 2 (Required)
                            </label>
                            <input type="file" name="proof_image2" id="proofImage2" accept="image/*"
                                class="border-2 border-gray-300 rounded-lg px-4 py-3 w-full focus:border-blue-500 focus:outline-none">
                            <div id="thumbnail2" class="mt-3"></div>
                        </div>
                    </div>

                    <!-- Reason Fields for Failed/Cancelled -->
                    <div id="reasonFields" class="hidden">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            📝 Reason for Status Change
                        </label>
                        <textarea name="reason" id="reason"
                            class="border-2 border-gray-300 rounded-lg px-4 py-3 w-full focus:border-blue-500 focus:outline-none"
                            rows="4" placeholder="Please provide a detailed reason..."></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" onclick="closeModal()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" id="confirmButton"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed"
                            disabled>
                            Confirm Update
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center text-gray-500 text-sm">
            <p>© {{ date('Y') }} Shipping Management System. All rights reserved.</p>
        </div>
    </div>

    <script>
    function openModal(orderId, status) {
        try {
            console.log(`Opening modal for order ${orderId} with status ${status}`);

            const modal = document.getElementById('statusModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalStatus = document.getElementById('modalStatus');
            const modalOrderId = document.getElementById('modalOrderId');
            const deliveredFields = document.getElementById('deliveredFields');
            const reasonFields = document.getElementById('reasonFields');
            const confirmButton = document.getElementById('confirmButton');
            const proofImage1 = document.getElementById('proofImage1');
            const proofImage2 = document.getElementById('proofImage2');
            const thumbnail1 = document.getElementById('thumbnail1');
            const thumbnail2 = document.getElementById('thumbnail2');
            const reasonTextarea = document.getElementById('reason');

            // Reset form state
            deliveredFields.classList.add('hidden');
            reasonFields.classList.add('hidden');
            confirmButton.disabled = (status === 'Delivered');
            thumbnail1.innerHTML = '';
            thumbnail2.innerHTML = '';
            proofImage1.value = '';
            proofImage2.value = '';
            reasonTextarea.value = '';

            // Set modal content
            modalOrderId.value = orderId;
            modalStatus.value = status;
            modalTitle.textContent = `Update Status: ${status} for Order ${orderId}`;
            document.getElementById('statusForm').action = `/orders/${orderId}/status`;

            // Show appropriate fields based on status
            if (status === 'Delivered') {
                deliveredFields.classList.remove('hidden');
            } else if (status === 'DeliveryFailed' || status === 'CancelledByBuyer') {
                reasonFields.classList.remove('hidden');
                confirmButton.disabled = false;
            } else {
                confirmButton.disabled = false;
            }

            // Show modal with animation
            modal.classList.add('modal-active');
        } catch (error) {
            console.error('Error in openModal:', error);
            alert('An error occurred while opening the modal. Please try again.');
        }
    }

    function closeModal() {
        try {
            document.getElementById('statusModal').classList.remove('modal-active');
        } catch (error) {
            console.error('Error in closeModal:', error);
        }
    }

    // Image preview and validation logic
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
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('w-32', 'h-32', 'object-cover', 'rounded-lg', 'border-2', 'border-gray-200',
                    'shadow-sm');
                container.innerHTML = '';
                container.appendChild(img);
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
    }

    // Close modal when clicking outside
    document.getElementById('statusModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        setupImageListeners();
        console.log('Shipping Management System initialized successfully');
    });
    </script>
</body>

</html>