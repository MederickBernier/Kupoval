@extends('emails.layout')

@section('content')
    <p>Bonjour {{ $order->recipient_name }},</p>

    <p>Nous avons traité un remboursement pour votre commande.</p>

    <table class="info-table">
        <tr>
            <td><strong>Numéro de commande :</strong></td>
            <td>{{ $order->id }}</td>
        </tr>
        <tr>
            <td><strong>Montant du remboursement :</strong></td>
            <td>${{ number_format($refundAmount, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Date du remboursement :</strong></td>
            <td>{{ $refundDate }}</td>
        </tr>
    </table>

    <p>Vous pouvez consulter les détails de votre commande à tout moment en cliquant sur le bouton ci-dessous :</p>

    <p style="text-align: center;">
        <a href="{{ route('orders.show', ['order' => $order->id]) }}"
           style="background-color: #dc3545; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
            Voir les détails de la commande
        </a>
    </p>

    <p>Si vous avez des questions ou des préoccupations, n'hésitez pas à contacter notre équipe de support.</p>

    <p><strong>Merci pour votre confiance !</strong></p>
@endsection
