<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('invoice.title', ['order' => $order->id]) }}</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 20px;
        }
        .invoice-container {
            width: 100%;
            max-width: 850px;
            margin: auto;
            padding: 30px;
            border: 1px solid #ddd;
            background: #fff;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .invoice-header {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            color: #444;
        }

        /* Tables */
        .info-table {
            width: 100%;
            font-size: 15px;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            vertical-align: top;
            text-align: left;
        }
        .info-table strong {
            display: inline; /* Keep labels inline */
            font-size: 15px;
            color: #222;
        }

        /* Custom Padding Classes */
        .p-0 { padding: 0 !important; }
        .p-5 { padding: 5px !important; }
        .p-10 { padding: 10px !important; }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f8f8;
            font-weight: bold;
            text-transform: uppercase;
        }
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Summary Table */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        .summary-table td {
            padding: 10px;
            text-align: right;
            font-size: 15px;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 13px;
            color: #777;
            margin-top: 30px;
        }

        /* Spacer */
        .spacer {
            height: 5px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            {{ __('invoice.title', ['order' => $order->id]) }}
        </div>

        <!-- Company Information -->
        <table class="info-table">
            <tr>
                <td class="p-5">
                    <strong>{{ $settings['site_name'] ?? 'Kupoval' }}</strong><br>
                    {{ $settings['site_address'] ?? '123 Business Street, City, Country' }}<br>
                    <strong>{{ __('invoice.company_email') }}:</strong> {{ $settings['site_email'] ?? 'contact@kupoval.art' }}<br>
                    <strong>{{ __('invoice.company_phone') }}:</strong> {{ $settings['site_phone'] ?? '+1 (514) 555-1234' }}
                </td>
                <td class="p-5" style="text-align: right;">
                    <strong>{{ __('invoice.date') }}:</strong> {{ $order->created_at->format('Y-m-d') }}
                </td>
            </tr>
        </table>

        <div class="spacer"></div>

        <!-- Client Information -->
        <table class="info-table">
            <tr>
                <td class="p-5">
                    <strong>{{ __('invoice.client_name') }}:</strong> {{ $clientName }}<br>
                    <strong>{{ __('invoice.email') }}:</strong> {{ $clientEmail }}
                    @if($clientPhone)
                        <br><strong>{{ __('invoice.phone') }}:</strong> {{ $clientPhone }}
                    @endif
                </td>
            </tr>
        </table>

        <div class="spacer"></div>

        <!-- Billing & Shipping Addresses -->
        <table class="info-table">
            <tr>
                <td class="p-5">
                    <strong>{{ __('invoice.billing_address') }}:</strong><br>
                    {{ $order->billingAddress->address }}<br>
                    {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->zipcode }}<br>
                    {{ $order->billingAddress->country }}
                </td>
                <td class="p-5" style="text-align: right;">
                    <strong>{{ __('invoice.shipping_address') }}:</strong><br>
                    {{ $shippingAddress->address }}<br>
                    {{ $shippingAddress->city }}, {{ $shippingAddress->state }} {{ $shippingAddress->zipcode }}<br>
                    {{ $shippingAddress->country }}
                </td>
            </tr>
        </table>

        <div class="spacer"></div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="p-5">{{ __('invoice.item') }}</th>
                    <th class="p-5" style="text-align: center;">{{ __('invoice.qty') }}</th>
                    <th class="p-5" style="text-align: right;">{{ __('invoice.unit_price') }}</th>
                    <th class="p-5" style="text-align: right;">{{ __('invoice.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td class="p-5">{{ $item->artwork->name }}</td>
                    <td class="p-5" style="text-align: center;">{{ $item->quantity }}</td>
                    <td class="p-5" style="text-align: right;">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="p-5" style="text-align: right;">${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="spacer"></div>

        <!-- Summary Table -->
        <table class="summary-table">
            <tr>
                <td class="p-5">{{ __('invoice.subtotal') }}:</td>
                <td class="p-5">${{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->tax_amount > 0)
            <tr>
                <td class="p-5">{{ __('invoice.tax') }} ({{ $order->tax_rate }}%):</td>
                <td class="p-5">${{ number_format($order->tax_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td class="p-5">{{ __('invoice.total_amount') }}:</td>
                <td class="p-5"><strong>${{ number_format($order->total, 2) }}</strong></td>
            </tr>
        </table>

        <div class="footer">
            {{ __('invoice.thank_you') }}
        </div>
    </div>
</body>
</html>
