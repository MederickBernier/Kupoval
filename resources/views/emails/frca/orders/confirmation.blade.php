@extends('emails.layout')

@section('content')
<h1>Merci, {{ $fullName ?? $user->username }} !</h1>

<p>Votre commande #{{ $order->id }} est en cours de traitement.</p>

<h2>Détails de la commande</h2>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="text-align: left;">Article</th>
            <th style="text-align: center;">Quantité</th>
            <th style="text-align: right;">Prix</th>
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

<p>Vous pouvez vérifier le statut de votre commande à tout moment.</p>

<p style="text-align: center;">
    <a href="{{ route('orders.show', ['order' => $order->id]) }}"
       style="background-color: #007bff; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
        Voir la commande
    </a>
</p>

<p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>

@endsection
