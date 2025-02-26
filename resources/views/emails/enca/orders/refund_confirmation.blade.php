@extends('emails.layout')

@section('content')
    <p>Hello {{ $order->recipient_name }},</p>

    <p>We have processed a refund for your order.</p>

    <table class="info-table">
        <tr>
            <td><strong>Order Number:</strong></td>
            <td>{{ $order->id }}</td>
        </tr>
        <tr>
            <td><strong>Refund Amount:</strong></td>
            <td>${{ number_format($refundAmount, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Refund Date:</strong></td>
            <td>{{ $refundDate }}</td>
        </tr>
    </table>

    <p>You can review your order details at any time by clicking the button below:</p>

    <p style="text-align: center;">
        <a href="{{ route('orders.show', ['order' => $order->id]) }}"
           style="background-color: #dc3545; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
            View Order Details
        </a>
    </p>

    <p>If you have any questions or concerns, feel free to contact our support team.</p>

    <p><strong>Thank you for choosing us!</strong></p>
@endsection
