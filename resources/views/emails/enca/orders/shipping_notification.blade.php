@extends('emails.layout')

@section('content')
    <p>Hello {{ $order->recipient_name }},</p>

    <p>Your order has been shipped! Here are the details:</p>

    <table class="info-table">
        <tr>
            <td><strong>Order Number:</strong></td>
            <td>{{ $order->id }}</td>
        </tr>
        <tr>
            <td><strong>Shipping Carrier:</strong></td>
            <td>{{ $carrier }}</td>
        </tr>
        <tr>
            <td><strong>Tracking Number:</strong></td>
            <td>{{ $trackingNumber }}</td>
        </tr>
        <tr>
            <td><strong>Estimated Delivery:</strong></td>
            <td>{{ $estimatedDelivery }}</td>
        </tr>
    </table>

    <p>You can track your package using the link below:</p>

    <p style="text-align: center;">
        <a href="{{ $trackingUrl }}"
           style="background-color: #007bff; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
            Track Your Shipment
        </a>
    </p>

    <p>If you have any questions, feel free to reach out to us.</p>

    <p><strong>Thank you for your order!</strong></p>
@endsection
