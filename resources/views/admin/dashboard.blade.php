@extends('layouts.admin')

@section('title', __('admin/dashboard.title'))

@section('page-title', __('admin/dashboard.page_title'))

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="p-4 bg-white shadow rounded-lg">
        <h3 class="text-lg font-semibold">{{ __('admin/dashboard.total_artworks') }}</h3>
        <p class="text-2xl font-bold text-green-600">{{ $totalArtworks }}</p>
    </div>

    <div class="p-4 bg-white shadow rounded-lg">
        <h3 class="text-lg font-semibold">{{ __('admin/dashboard.total_users') }}</h3>
        <p class="text-2xl font-bold text-blue-600">{{ $totalUsers }}</p>
    </div>

    <div class="p-4 bg-white shadow rounded-lg">
        <h3 class="text-lg font-semibold">{{ __('admin/dashboard.total_events') }}</h3>
        <p class="text-2xl font-bold text-purple-600">{{ $totalEvents }}</p>
    </div>
</div>

<!-- Order Summary & Chart Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    <!-- Order Summary Card -->
    <div class="p-6 bg-white shadow rounded-lg">
        <h3 class="text-lg font-semibold mb-4">{{ __('admin/dashboard.orders_overview') }}</h3>
        <p class="text-2xl font-bold text-green-600">{{ $totalOrders }} {{ __('admin/dashboard.total_orders') }}</p>

        <div class="mt-4">
            <ul class="list-disc list-inside text-gray-700">
                @foreach(['pending' => 'yellow', 'completed' => 'green', 'canceled' => 'red', 'refunded' => 'blue'] as $status => $color)
                    <li class="text-lg">
                        <span class="text-{{ $color }}-600 font-bold">
                            {{ $orderStatusCounts[$status] ?? 0 }}
                        </span>
                        {{ __('admin/orders.status.' . $status) }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

<!-- Order Status Chart (Smaller Size) -->
<div class="p-6 bg-white shadow rounded-lg flex flex-col items-center">
    <h3 class="text-lg font-semibold mb-4">{{ __('admin/dashboard.order_status_chart') }}</h3>
    <div class="w-64 h-64"> <!-- Adjust width and height -->
        <canvas id="orderStatusChart"></canvas>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById("orderStatusChart").getContext("2d");

        new Chart(ctx, {
            type: "pie", // Change to "bar" if you prefer
            data: {
                labels: [
                    "{{ __('admin/orders.status.pending') }}",
                    "{{ __('admin/orders.status.completed') }}",
                    "{{ __('admin/orders.status.canceled') }}",
                    "{{ __('admin/orders.status.refunded') }}"
                ],
                datasets: [{
                    data: [
                        {{ $orderStatusCounts['pending'] ?? 0 }},
                        {{ $orderStatusCounts['completed'] ?? 0 }},
                        {{ $orderStatusCounts['canceled'] ?? 0 }},
                        {{ $orderStatusCounts['refunded'] ?? 0 }}
                    ],
                    backgroundColor: ["#facc15", "#22c55e", "#ef4444", "#3b82f6"]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Allow custom size
                plugins: {
                    legend: {
                        position: "bottom", // Moves legend to bottom for better space usage
                        labels: {
                            font: {
                                size: 12 // Reduce legend font size
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
