<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('invoice.title', ['order' => $order->id]) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .invoice-container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .invoice-header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .company-info {
            text-align: left;
            font-size: 12px;
            margin-bottom: 20px;
        }
        .invoice-info {
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .invoice-info td {
            padding: 8px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .items-table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .total {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
            padding: 10px;
            border-top: 2px solid #ddd;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #777;
            margin-top: 30px;
        }
        .logo {
            text-align: center;
            margin-bottom: 10px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .summary-table td {
            padding: 10px;
            text-align: right;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Company Logo -->
        <div class="logo">
            <img src="{{ asset('storage/logo.png') }}" alt="Company Logo" height="60">
        </div>

        <!-- Invoice Header -->
        <div class="invoice-header">
            {{ __('invoice.title', ['order' => $order->id]) }}
        </div>

        <!-- Company & Customer Info -->
        <table class="invoice-info">
            <tr>
                <td>
                    <strong>{{ $settings['site_name'] ?? 'Kupoval' }}</strong><br>
                    {{ $settings['site_address'] ?? '123 Business Street, City, Country' }}<br>
                    {{ __('invoice.company_email') }}: {{ $settings['site_email'] ?? 'contact@kupoval.art' }}<br>
                    {{ __('invoice.company_phone') }}: {{ $settings['site_phone'] ?? '+1 (514) 555-1234' }}
                </td>
                <td style="text-align: right;">
                    <strong>{{ __('invoice.date') }}:</strong> {{ $order->created_at->format('Y-m-d') }}<br>
                    <strong>{{ __('invoice.customer') }}:</strong> {{ $order->recipient_name }}<br>
                    <strong>{{ __('invoice.email') }}:</strong> {{ $order->recipient_email }}
                </td>
            </tr>
        </table>

        <!-- Billing & Shipping -->
        <table class="invoice-info">
            <tr>
                <td>
                    <strong>{{ __('invoice.billing_address') }}</strong><br>
                    {{ $order->billingAddress->address }}<br>
                    {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->zipcode }}<br>
                    {{ $order->billingAddress->country }}
                </td>
                <td style="text-align: right;">
                    <strong>{{ __('invoice.shipping_address') }}</strong><br>
                    {{ $shippingAddress->address }}<br>
                    {{ $shippingAddress->city }}, {{ $shippingAddress->state }} {{ $shippingAddress->zipcode }}<br>
                    {{ $shippingAddress->country }}
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>{{ __('invoice.item') }}</th>
                    <th>{{ __('invoice.qty') }}</th>
                    <th>{{ __('invoice.unit_price') }}</th>
                    <th>{{ __('invoice.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->artwork->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td>${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary Table -->
        <table class="summary-table">
            <tr>
                <td>{{ __('invoice.subtotal') }}:</td>
                <td>${{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->tax_amount > 0)
            <tr>
                <td>{{ __('invoice.tax') }} ({{ $order->tax_rate }}%):</td>
                <td>${{ number_format($order->tax_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td>{{ __('invoice.total_amount') }}:</td>
                <td><strong>${{ number_format($order->total, 2) }}</strong></td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            {{ __('invoice.thank_you') }}
        </div>
    </div>
</body>
</html>
