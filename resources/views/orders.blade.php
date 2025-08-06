<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Shipping Management</h1>
        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif
        <table class="min-w-full bg-white shadow-md rounded">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Order ID</th>
                    <th class="py-3 px-6 text-left">Seller</th>
                    <th class="py-3 px-6 text-left">Status</th>
                    <th class="py-3 px-6 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr class="hover:bg-gray-100 border-b">
                    <td class="py-3 px-6">{{ $order->order_id }}</td>
                    <td class="py-3 px-6">{{ $order->seller }}</td>
                    <td class="py-3 px-6">{{ $order->status }}</td>
                    <td class="py-3 px-6">
                        <form action="{{ route('update.status', $order->order_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status" class="border p-1">
                                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>
                                    Processing</option>
                                <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>
                                    Delivered</option>
                                <option value="CancelledByBuyer"
                                    {{ $order->status == 'CancelledByBuyer' ? 'selected' : '' }}>
                                    Cancelled</option>
                            </select>
                            <button type="submit" class="ml-2 bg-blue-500 text-white p-1 rounded">Update</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>