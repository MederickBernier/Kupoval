@extends('emails.layout')

@section('content')
    <p>{{ __('invoice.payment_receipt_greeting', ['name' => $order->recipient_name]) }}</p>

    <p>{{ __('invoice.payment_receipt_message') }}</p>

    <table class="info-table">
        <tr>
            <td><strong>{{ __('invoice.order_number') }}</strong></td>
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
            <td><strong>{{ __('invoice.amount_paid') }}</strong></td>
            <td>${{ number_format($payment->amount, 2) }}</td>
        </tr>
        <tr>
            <td><strong>{{ __('invoice.payment_date') }}</strong></td>
            <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
        </tr>
    </table>

    <p>{{ __('invoice.payment_receipt_footer') }}</p>

    <p><strong>{{ __('invoice.thank_you') }}</strong></p>
@endsection
