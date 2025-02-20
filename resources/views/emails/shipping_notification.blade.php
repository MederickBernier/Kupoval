@extends('emails.layout')

@section('content')
    <p>{{ __('email.shipping_notification_greeting', ['name' => $order->recipient_name]) }}</p>

    <p>{{ __('email.shipping_notification_message') }}</p>

    <table class="info-table">
        <tr>
            <td><strong>{{ __('email.order_number') }}</strong></td>
            <td>{{ $order->id }}</td>
        </tr>
        <tr>
            <td><strong>{{ __('email.shipping_carrier') }}</strong></td>
            <td>{{ $carrier }}</td>
        </tr>
        <tr>
            <td><strong>{{ __('email.tracking_number') }}</strong></td>
            <td>{{ $trackingNumber }}</td>
        </tr>
        <tr>
            <td><strong>{{ __('email.estimated_delivery') }}</strong></td>
            <td>{{ $estimatedDelivery }}</td>
        </tr>
    </table>

    <p>{{ __('email.shipping_notification_footer') }}</p>

    <p><strong>{{ __('email.thank_you') }}</strong></p>
@endsection
