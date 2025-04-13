<!DOCTYPE html>
<html>
<head>
    <title>Basket</title>
</head>
<body>
    <h1>Product Catalogue</h1>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    <a href="{{ route('products.create') }}">Add Product</a>


    <form method="POST" action="{{ route('basket.clear') }}">
        @csrf
        <button type="submit">Clear Basket</button>
    </form>

    <ul>
        @foreach($products as $product)
            <li>
                {{ $product->name }} ({{ $product->sku }}) - ${{ number_format($product->price, 2) }}
                <form method="POST" action="{{ route('basket.add') }}" style="display:inline;">
                    @csrf
                    <input type="hidden" name="sku" value="{{ $product->sku }}">
                    <button type="submit">Add to Basket</button>
                </form>
            </li>
        @endforeach
    </ul>

    <h2>Basket</h2>
    @if(!empty($basket))
        <ul>
            @foreach($basket as $sku => $item)
                <li>{{ $item['name'] }} x {{ $item['quantity'] }} - ${{ number_format($item['price'] * $item['quantity'], 2) }}</li>
            @endforeach
        </ul>
    
        <p><strong>Subtotal:</strong> ${{ number_format($subtotal, 2) }}</p>
        <p><strong>Delivery:</strong> ${{ number_format($delivery, 2) }}</p>
        <p><strong>Total:</strong> ${{ number_format($total, 2) }}</p>
    @else
        <p>Your basket is empty.</p>
    @endif
</body>
</html>
