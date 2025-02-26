@extends('emails.layout')

@section('content')
<h1>Thank you, {{ $fullName ?? $user->username }}!</h1>

<p>Your order #{{ $order->id }} is now being processed.</p>

<h2>Order Details</h2>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="text-align: left;">Item</th>
            <th style="text-align: center;">Quantity</th>
            <th style="text-align: right;">Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->artwork->name }}</td>
            <td style="text-align: center;">{{ $item->quantity }}</td>
            <td style="text-align: right;">${{ number_format($item->unit_price, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<p><strong>Total: ${{ number_format($order->total, 2) }}</strong></p>

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

<p>You can check your order status anytime.</p>

<p style="text-align: center;">
    <a href="{{ route('orders.show', ['order' => $order->id]) }}"
       style="background-color: #007bff; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
        View Order
    </a>
</p>

<p>If you have any questions, feel free to contact us.</p>

@endsection
