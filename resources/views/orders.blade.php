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
    </style>
</head>

<body class="min-h-screen gradient-bg">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div
                    class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                    <span class="text-white text-xl">🚚</span>
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                    Shipping Management
                </h1>
            </div>
            <p class="text-gray-600 text-lg">Manage and track all your shipping orders in one place</p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
        <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="text-green-500 text-xl">✨</span>
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $orders->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="text-blue-600 text-xl">📦</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600">
                            {{ $orders->where('status', 'Pending')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <span class="text-yellow-600 text-xl">⏳</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Processing</p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{ $orders->where('status', 'Processing')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="text-blue-600 text-xl">🔄</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Delivered</p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ $orders->where('status', 'Delivered')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <span class="text-green-600 text-xl">✅</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-xl font-semibold text-gray-800">Recent Orders</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th
                                class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">
                                Order Details
                            </th>
                            <th
                                class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">
                                Customer
                            </th>
                            <th
                                class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">
                                Status
                            </th>
                            <th
                                class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">
                                Amount
                            </th>
                            <th
                                class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-6 px-6">
                                <div>
                                    <div class="font-semibold text-gray-900 text-lg">{{ $order->order_id }}</div>
                                    <div class="text-gray-600 text-sm">{{ $order->seller }}</div>
                                    <div class="text-gray-400 text-xs mt-1">{{ $order->created_at->format('Y-m-d') }}
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr($order->customer_name ?? 'N/A', 0, 1)) }}{{ strtoupper(substr(explode(' ', $order->customer_name ?? 'A')[1] ?? '', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $order->customer_name ?? 'N/A' }}
                                        </div>
                                        <div class="text-gray-500 text-sm">Customer</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-6">
                                @php
                                $statusConfig = [
                                'Pending' => ['class' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 'icon' =>
                                '⏳'],
                                'Processing' => ['class' => 'bg-blue-100 text-blue-800 border-blue-200', 'icon' =>
                                '🔄'],
                                'Delivered' => ['class' => 'bg-green-100 text-green-800 border-green-200', 'icon' =>
                                '✅'],
                                'CancelledByBuyer' => ['class' => 'bg-red-100 text-red-800 border-red-200', 'icon' =>
                                '❌']
                                ];
                                $config = $statusConfig[$order->status] ?? ['class' => 'bg-gray-100 text-gray-800
                                border-gray-200', 'icon' => '📦'];
                                @endphp
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-full text-sm font-medium border {{ $config['class'] }}">
                                    <span>{{ $config['icon'] }}</span>
                                    {{ $order->status == 'CancelledByBuyer' ? 'Cancelled' : $order->status }}
                                </span>
                            </td>
                            <td class="py-6 px-6">
                                <div class="font-semibold text-gray-900 text-lg">
                                    ${{ number_format($order->amount ?? 0, 2) }}</div>
                            </td>
                            <td class="py-6 px-6">
                                <form action="{{ route('update.status', $order->order_id) }}" method="POST"
                                    class="flex items-center gap-3">
                                    @csrf
                                    @method('PUT')
                                    <select name="status"
                                        class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white shadow-sm">
                                        <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="Processing"
                                            {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>
                                            Delivered</option>
                                        <option value="CancelledByBuyer"
                                            {{ $order->status == 'CancelledByBuyer' ? 'selected' : '' }}>Cancelled
                                        </option>
                                    </select>
                                    <button type="submit"
                                        class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                        Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-gray-500 text-sm">
            <p>© 2024 Shipping Management System. All rights reserved.</p>
        </div>
    </div>
</body>

</html>