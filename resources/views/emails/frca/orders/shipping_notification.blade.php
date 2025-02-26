@extends('emails.layout')

@section('content')
    <p>Bonjour {{ $order->recipient_name }},</p>

    <p>Votre commande a été expédiée ! Voici les détails :</p>

    <table class="info-table">
        <tr>
            <td><strong>Numéro de commande :</strong></td>
            <td>{{ $order->id }}</td>
        </tr>
        <tr>
            <td><strong>Transporteur :</strong></td>
            <td>{{ $carrier }}</td>
        </tr>
        <tr>
            <td><strong>Numéro de suivi :</strong></td>
            <td>{{ $trackingNumber }}</td>
        </tr>
        <tr>
            <td><strong>Date de livraison estimée :</strong></td>
            <td>{{ $estimatedDelivery }}</td>
        </tr>
    </table>

    <p>Vous pouvez suivre votre colis en cliquant sur le lien ci-dessous :</p>

    <p style="text-align: center;">
        <a href="{{ $trackingUrl }}"
           style="background-color: #007bff; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
            Suivre votre colis
        </a>
    </p>

    <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>

    <p><strong>Merci pour votre commande !</strong></p>
@endsection
