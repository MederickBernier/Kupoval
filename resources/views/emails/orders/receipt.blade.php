@extends('emails.layout')

@section('content')
    <p>{{ __('invoice.thank_you') }}, {{ $order->recipient_name }}!</p>

    <p>{{ __('emails.payment_receipt.message') }}</p>

    <table class="info-table">
        <tr>
            <td><strong>{{ __('invoice.title', ['order' => $order->id]) }}</strong></td>
            <td>{{ $order->id }}</td>
        </tr>
        <tr>
            <td><strong>{{ __('invoice.payment_method') }}</strong></td>
            <td>{{ ucfirst($payment->payment_method) }}</td>
        </tr>
        <tr>
            <td><strong>{{ __('invoice.payment_status') }}</strong></td>
            <td>{{ ucfirst($payment->status) }}</td>
        </tr>
        <tr>
            <td><strong>{{ __('invoice.total_amount') }}</strong></td>
            <td>${{ number_format($payment->amount, 2) }}</td>
        </tr>
        <tr>
            <td><strong>{{ __('invoice.date') }}</strong></td>
            <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
        </tr>
    </table>

    <h2>{{ __('invoice.billing_address') }}</h2>
    <p>
        {{ $order->billingAddress->address }}<br>
        {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->zipcode }}<br>
        {{ $order->billingAddress->country }}
    </p>

    <h2>{{ __('invoice.shipping_address') }}</h2>
    @if(isset($shippingAddress) && !empty($shippingAddress->address))
        <p>
            {{ $shippingAddress->address }}<br>
            {{ $shippingAddress->city }}, {{ $shippingAddress->state }} {{ $shippingAddress->zipcode }}<br>
            {{ $shippingAddress->country }}
        </p>
    @else
        <p>{{ __('emails.order_confirmation.no_address') }}</p>
    @endif

    <p>{{ __('emails.payment_receipt.footer') }}</p>

    <p><strong>{{ __('invoice.thank_you') }}</strong></p>
@endsection
