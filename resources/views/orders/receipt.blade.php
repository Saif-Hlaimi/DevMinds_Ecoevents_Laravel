@extends('layouts.app')
@section('title', 'Reçu de commande #' . $order->id)

@push('styles')
<style>
    .receipt-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        padding: 40px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    
    .receipt-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .receipt-header h1 {
        color: #28a745;
        margin-bottom: 10px;
    }
    
    .receipt-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .receipt-items table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }
    
    .receipt-items th {
        background: #f8f9fa;
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #eee;
    }
    
    .receipt-items td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }
    
    .receipt-total {
        text-align: right;
        font-size: 1.2em;
        margin-bottom: 30px;
    }
    
    .receipt-actions {
        text-align: center;
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        
        .receipt-container {
            box-shadow: none;
            border: none;
            padding: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="container pt-130 pb-130">
    <div class="receipt-container">
        <div class="receipt-header">
            <h3><i class="fa-solid fa-check-circle me-2"></i>Merci pour votre achat. Voici votre reçu. !</h3>
        </div>
        
        <div class="receipt-info">
            <div>
                <h5>Commande #{{ $order->id }}</h5>
                <p>Date: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="text-end">
                <h5>Adresse de livraison</h5>
                <p>{{ $order->customer_name }}</p>
                <p>{{ $order->customer_address }}</p>
                <p>{{ $order->customer_city }}, {{ $order->customer_postal_code }}</p>
                <p>Email: {{ $order->customer_email }}</p>
                <p>Tél: {{ $order->customer_phone }}</p>
            </div>
        </div>
        
        <div class="receipt-items">
            <h5>Articles commandés</h5>
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2, ',', ' ') }} €</td>
                        <td>{{ number_format($item->quantity * $item->price, 2, ',', ' ') }} €</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="receipt-total">
            <strong>Total payé : {{ number_format($order->total, 2, ',', ' ') }} €</strong>
            <p class="small text-muted">Méthode de paiement : 
                @switch($order->payment_method)
                    @case('cash')
                        Paiement à la livraison
                        @break
                    @case('card')
                        Carte bancaire
                        @break
                    @case('transfer')
                        Virement bancaire
                        @break
                    @default
                        {{ ucfirst($order->payment_method) }}
                @endswitch
            </p>
        </div>
        
        @if($order->notes)
        <div class="mb-4">
            <h5>Notes</h5>
            <p class="text-muted">{{ $order->notes }}</p>
        </div>
        @endif
        
        <div class="receipt-actions">
            
            <a href="{{ route('orders.download-receipt', $order->id) }}" class="btn btn-success me-2 no-print">
                <i class="fa-solid fa-download me-2"></i>Télécharger PDF
            </a>
           
        </div>
    </div>
</div>
@endsection