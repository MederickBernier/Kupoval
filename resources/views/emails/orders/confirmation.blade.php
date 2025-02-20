@extends('emails.layout')

@section('content')
<h1>{{ trans('emails/order_confirmation.thank_you', ['name' => isset($fullName) && !empty($fullName) ? $fullName : $user->username]) }}</h1>

<p>{{ trans('emails/order_confirmation.processing', ['order' => $order->id]) }}</p>

<h2>{{ trans('emails/order_confirmation.details') }}</h2>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="text-align: left;">{{ trans('emails/order_confirmation.item') }}</th>
            <th style="text-align: center;">{{ trans('emails/order_confirmation.quantity') }}</th>
            <th style="text-align: right;">{{ trans('emails/order_confirmation.price') }}</th>
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

<p><strong>{{ trans('emails/order_confirmation.total') }} ${{ number_format($order->total, 2) }}</strong></p>

<h2>{{ trans('emails/order_confirmation.shipping_address') }}</h2>

@if(isset($shippingAddress) && !empty($shippingAddress->address))
<p>
    {{ $shippingAddress->address }}<br>
    {{ $shippingAddress->city }}, {{ $shippingAddress->state }} {{ $shippingAddress->zipcode }}<br>
    {{ $shippingAddress->country }}
</p>
@else
<p>{{ trans('emails/order_confirmation.no_address') }}</p>
@endif

<p>{{ trans('emails/order_confirmation.check_status') }}</p>

<p style="text-align: center;">
    <a href="{{ route('orders.show', ['order' => $order->id]) }}"
       style="background-color: #007bff; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
        {{ trans('emails/order_confirmation.view_order') }}
    </a>
</p>

<p>{{ trans('emails/order_confirmation.contact_us') }}</p>

@endsection
