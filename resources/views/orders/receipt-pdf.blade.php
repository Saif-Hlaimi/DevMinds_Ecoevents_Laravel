<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reçu de commande #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #28a745;
        }
        
        .header h1 {
            color: #28a745;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }
        
        .info-box {
            flex: 1;
            padding: 0 10px;
        }
        
        .info-box h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .info-box p {
            margin: 5px 0;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        
        .items-table th {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        
        .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        
        .total-section {
            text-align: right;
            margin-bottom: 20px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
        }
        
        .total-amount {
            font-size: 16px;
            font-weight: bold;
            color: #28a745;
        }
        
        .notes-section {
            margin-top: 25px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo h2 {
            color: #28a745;
            margin: 0;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="logo">
        <h2>{{ config('app.name', 'Laravel') }}</h2>
    </div>
    
    <div class="header">
        <h1>REÇU DE COMMANDE</h1>
        <p>Merci pour votre achat !</p>
    </div>
    
    <div class="info-section">
        <div class="info-box">
            <h3>Informations de commande</h3>
            <p><strong>N° Commande :</strong> #{{ $order->id }}</p>
            <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
        
        <div class="info-box">
            <h3>Adresse de livraison</h3>
            <p><strong>{{ $order->customer_name }}</strong></p>
            <p>{{ $order->customer_address }}</p>
            <p>{{ $order->customer_city }}, {{ $order->customer_postal_code }}</p>
            <p><strong>Email :</strong> {{ $order->customer_email }}</p>
            <p><strong>Tél :</strong> {{ $order->customer_phone }}</p>
        </div>
        
        <div class="info-box">
            <h3>Informations de paiement</h3>
            <p><strong>Méthode :</strong> 
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
            @if($order->payment_intent_id)
            <p><strong>Réf. paiement :</strong> {{ $order->payment_intent_id }}</p>
            @endif
        </div>
    </div>
    
    <table class="items-table">
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
    
    <div class="total-section">
        <p class="total-amount">Total : {{ number_format($order->total, 2, ',', ' ') }} €</p>
    </div>
    
    @if($order->notes)
    <div class="notes-section">
        <h3>Notes :</h3>
        <p>{{ $order->notes }}</p>
    </div>
    @endif
    
    <div class="footer">
        <p>Reçu généré le {{ now()->format('d/m/Y à H:i') }} | {{ config('app.name', 'Laravel') }}</p>
        <p>Pour toute question concernant votre commande, contactez notre service client.</p>
    </div>
</body>
</html>