@extends('layouts.admin')

@section('title', __('admin/dashboard.title'))

@section('page-title', __('admin/dashboard.page_title'))

@push('styles')
<style>
    .stats-card {
        transition: all 0.2s ease;
    }
    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
    }
    .chart-container {
        height: 300px;
        position: relative;
    }
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div x-data="dashboard()">
    <!-- Stats Overview -->
    <div class="stats-grid mb-6">
        <div class="p-5 bg-white shadow rounded-lg stats-card border-l-4 border-green-500">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-semibold text-gray-700">{{ __('admin/dashboard.total_artworks') }}</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-green-600">{{ number_format($totalArtworks) }}</p>
        </div>

        <div class="p-5 bg-white shadow rounded-lg stats-card border-l-4 border-blue-500">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-semibold text-gray-700">{{ __('admin/dashboard.total_users') }}</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($totalUsers) }}</p>
        </div>

        <div class="p-5 bg-white shadow rounded-lg stats-card border-l-4 border-purple-500">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-semibold text-gray-700">{{ __('admin/dashboard.total_events') }}</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-purple-600">{{ number_format($totalEvents) }}</p>
        </div>

        <div class="p-5 bg-white shadow rounded-lg stats-card border-l-4 border-amber-500">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-semibold text-gray-700">{{ __('admin/dashboard.total_orders') }}</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-amber-600">{{ number_format($totalOrders) }}</p>
        </div>
    </div>

    <!-- Order Summary & Chart Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Order Summary Card -->
        <div class="p-6 bg-white shadow rounded-lg">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-700">{{ __('admin/dashboard.orders_overview') }}</h3>
            </div>

            <div class="mb-6">
                <div class="flex items-center mb-1">
                    <h4 class="text-base font-medium text-gray-700">{{ __('admin/dashboard.total_orders') }}</h4>
                    <span class="ml-auto text-xl font-bold text-amber-600">{{ number_format($totalOrders) }}</span>
                </div>
                <div class="h-1 w-full bg-amber-100">
                    <div class="h-1 bg-amber-500 w-full"></div>
                </div>
            </div>

            <div class="space-y-4">
                @php
                    $statusColors = [
                        'pending' => 'yellow',
                        'completed' => 'green',
                        'canceled' => 'red',
                        'refunded' => 'blue'
                    ];
                @endphp

                @foreach($statusColors as $status => $color)
                    @php
                        $count = $orderStatusCounts[$status] ?? 0;
                        $percentage = $totalOrders > 0 ? ($count / $totalOrders) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center">
                                <span class="inline-block w-3 h-3 rounded-full bg-{{ $color }}-500 mr-2"></span>
                                <span class="text-sm text-gray-700">{{ __('admin/orders.status.' . $status) }}</span>
                            </div>
                            <div class="text-sm">
                                <span class="font-medium text-{{ $color }}-600">{{ $count }}</span>
                                <span class="text-gray-500 text-xs">({{ round($percentage) }}%)</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-{{ $color }}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Order Status Chart -->
        <div class="p-6 bg-white shadow rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">{{ __('admin/dashboard.order_status_chart') }}</h3>
                <div class="flex space-x-1">
                    <button @click="setChartType('pie')"
                            :class="{ 'bg-blue-600 text-white': chartType === 'pie', 'bg-gray-200 text-gray-700': chartType !== 'pie' }"
                            class="px-3 py-1 rounded-l text-sm transition-colors">
                        {{ __('admin/dashboard.pie') }}
                    </button>
                    <button @click="setChartType('bar')"
                            :class="{ 'bg-blue-600 text-white': chartType === 'bar', 'bg-gray-200 text-gray-700': chartType !== 'bar' }"
                            class="px-3 py-1 rounded-r text-sm transition-colors">
                        {{ __('admin/dashboard.bar') }}
                    </button>
                </div>
            </div>
            <div class="chart-container">
                <canvas x-ref="orderStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    function dashboard() {
        return {
            chartType: 'pie',
            chart: null,
            chartData: {
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
                    backgroundColor: ["#facc15", "#22c55e", "#ef4444", "#3b82f6"],
                    borderColor: ["#ffffff", "#ffffff", "#ffffff", "#ffffff"],
                    borderWidth: 2
                }]
            },

            init() {
                this.$nextTick(() => {
                    this.initChart();
                });
            },

            initChart() {
                const ctx = this.$refs.orderStatusChart.getContext('2d');

                // If chart already exists, destroy it
                if (this.chart) {
                    this.chart.destroy();
                }

                // Configure options based on chart type
                const options = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                boxWidth: 12,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.formattedValue;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((context.raw / total) * 100) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                };

                // Add scales for bar chart
                if (this.chartType === 'bar') {
                    options.scales = {
                        x: {
                            display: true
                        },
                        y: {
                            display: true,
                            beginAtZero: true,
                            ticks: {
                                precision: 0 // Only show whole numbers
                            }
                        }
                    };
                } else {
                    options.scales = {
                        x: {
                            display: false
                        },
                        y: {
                            display: false
                        }
                    };
                }

                // Create new chart
                this.chart = new Chart(ctx, {
                    type: this.chartType,
                    data: this.chartData,
                    options: options
                });
            },

            setChartType(type) {
                this.chartType = type;
                this.initChart();
            }
        };
    }
</script>
@endpush
@endsection
