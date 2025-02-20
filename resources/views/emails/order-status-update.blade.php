<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Update - #{{ $order->id }}</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px; background-color: #f8f8f8;">
    <div style="max-width: 600px; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333;">Order #{{ $order->id }} Update</h2>
        <p>Dear {{ $order->recipient_name }},</p>
        <p>Your order status has been updated to: <strong>{{ $status }}</strong>.</p>

        <h3 style="border-bottom: 2px solid #ddd; padding-bottom: 5px;">Order Summary</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align: left; padding: 8px; border-bottom: 2px solid #ddd;">Item</th>
                    <th style="text-align: center; padding: 8px; border-bottom: 2px solid #ddd;">Qty</th>
                    <th style="text-align: right; padding: 8px; border-bottom: 2px solid #ddd;">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $item->artwork->name }}</td>
                        <td style="padding: 8px; text-align: center; border-bottom: 1px solid #ddd;">{{ $item->quantity }}</td>
                        <td style="padding: 8px; text-align: right; border-bottom: 1px solid #ddd;">${{ number_format($item->unit_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="font-size: 18px; font-weight: bold; text-align: right; margin-top: 10px;">Total: ${{ number_format($order->total, 2) }}</p>

        <p>If you have any questions, feel free to contact us.</p>

        <p style="text-align: center; margin-top: 20px;">
            <a href="{{ route('orders.invoice', $order->id) }}" style="display: inline-block; padding: 10px 20px; background-color: #3490dc; color: white; text-decoration: none; border-radius: 5px;">View Order</a>
        </p>

        <p style="font-size: 12px; text-align: center; color: #666;">Thank you for supporting our artists!</p>
    </div>
</body>
</html>
