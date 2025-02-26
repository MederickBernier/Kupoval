@extends('emails.layout')

@section('content')
    <p>Merci, {{ $order->recipient_name }} !</p>

    <p>Votre paiement a été traité avec succès. Voici les détails de votre transaction :</p>

    <table class="info-table">
        <tr>
            <td><strong>Facture #</strong></td>
            <td>{{ $order->id }}</td>
        </tr>
        <tr>
            <td><strong>Méthode de paiement</strong></td>
            <td>{{ ucfirst($payment->payment_method) }}</td>
        </tr>
        <tr>
            <td><strong>Statut du paiement</strong></td>
            <td>{{ ucfirst($payment->status) }}</td>
        </tr>
        <tr>
            <td><strong>Montant total</strong></td>
            <td>${{ number_format($payment->amount, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Date</strong></td>
            <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
        </tr>
    </table>

    <h2>Adresse de facturation</h2>
    <p>
        {{ $order->billingAddress->address }}<br>
        {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->zipcode }}<br>
        {{ $order->billingAddress->country }}
    </p>

    <h2>Adresse de livraison</h2>
    @if(isset($shippingAddress) && !empty($shippingAddress->address))
        <p>
            {{ $shippingAddress->address }}<br>
            {{ $shippingAddress->city }}, {{ $shippingAddress->state }} {{ $shippingAddress->zipcode }}<br>
            {{ $shippingAddress->country }}
        </p>
    @else
        <p>Aucune adresse de livraison fournie.</p>
    @endif

    <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>

    <p><strong>Merci pour votre achat !</strong></p>
@endsection
