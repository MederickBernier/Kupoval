@extends('emails.layout')

@section('content')
    <p>Thank you, {{ $order->recipient_name }}!</p>

    <p>Your payment has been successfully processed. Below are the details of your transaction:</p>

    <table class="info-table">
        <tr>
            <td><strong>Invoice #</strong></td>
            <td>{{ $order->id }}</td>
        </tr>
        <tr>
            <td><strong>Payment Method</strong></td>
            <td>{{ ucfirst($payment->payment_method) }}</td>
        </tr>
        <tr>
            <td><strong>Payment Status</strong></td>
            <td>{{ ucfirst($payment->status) }}</td>
        </tr>
        <tr>
            <td><strong>Total Amount</strong></td>
            <td>${{ number_format($payment->amount, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Date</strong></td>
            <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
        </tr>
    </table>

    <h2>Billing Address</h2>
    <p>
        {{ $order->billingAddress->address }}<br>
        {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->zipcode }}<br>
        {{ $order->billingAddress->country }}
    </p>

    <h2>Shipping Address</h2>
    @if(isset($shippingAddress) && !empty($shippingAddress->address))
        <p>
            {{ $shippingAddress->address }}<br>
            {{ $shippingAddress->city }}, {{ $shippingAddress->state }} {{ $shippingAddress->zipcode }}<br>
            {{ $shippingAddress->country }}
        </p>
    @else
        <p>No shipping address provided.</p>
    @endif

    <p>If you have any questions, feel free to contact us.</p>

    <p><strong>Thank you for your purchase!</strong></p>
@endsection
