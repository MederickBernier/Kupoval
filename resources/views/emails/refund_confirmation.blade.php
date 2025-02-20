@extends('emails.layout')

@section('content')
    <p>{{ __('email.refund_confirmation_greeting', ['name' => $order->recipient_name]) }}</p>

    <p>{{ __('email.refund_confirmation_message', ['amount' => $refundAmount, 'date' => $refundDate]) }}</p>

    <table class="info-table">
        <tr>
            <td><strong>{{ __('email.order_number') }}</strong></td>
            <td>{{ $order->id }}</td>
        </tr>
        <tr>
            <td><strong>{{ __('email.refund_amount') }}</strong></td>
            <td>${{ $refundAmount }}</td>
        </tr>
        <tr>
            <td><strong>{{ __('email.refund_date') }}</strong></td>
            <td>{{ $refundDate }}</td>
        </tr>
    </table>

    <p>{{ __('email.refund_confirmation_footer') }}</p>

    <p><strong>{{ __('email.thank_you') }}</strong></p>
@endsection
