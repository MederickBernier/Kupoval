@extends('emails.layout')

@section('content')
<h1>{{ __('Thank you for your order, :name!', ['name' => $user->profile->full_name ?? $user->username]) }}</h1>

<p>{{ __('We are processing your order #:order.', ['order' => $order->id]) }}</p>

<h2>{{ __('Order Details') }}</h2>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="text-align: left;">{{ __('Item') }}</th>
            <th style="text-align: center;">{{ __('Quantity') }}</th>
            <th style="text-align: right;">{{ __('Price') }}</th>
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

<p><strong>{{ __('Total:') }} ${{ number_format($order->total, 2) }}</strong></p>

<h2>{{ __('Shipping Address') }}</h2>
<p>
    {{ $shippingAddress->address }}<br>
    {{ $shippingAddress->city }}, {{ $shippingAddress->state }} {{ $shippingAddress->zipcode }}<br>
    {{ $shippingAddress->country }}
</p>

<p>{{ __('You can check your order status anytime in your account.') }}</p>

<p style="text-align: center;">
    <a href="{{ route('orders.show', ['order' => $order->id]) }}"
       style="background-color: #007bff; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
        {{ __('View Order') }}
    </a>
</p>

<p>{{ __('If you have any questions, feel free to contact us.') }}</p>

@endsection
