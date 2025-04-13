<x-app-layout>
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Product Catalogue</h1>
            <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('basket.destroy') }}" method="POST" class="mb-4">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Clear Basket</button>
        </form>

        <div class="row row-cols-1 row-cols-md-2 g-4 mb-5">
            @foreach($products as $product)
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <h6 class="card-subtitle text-muted mb-2">SKU: {{ $product->sku }}</h6>
                            <p class="card-text">${{ number_format($product->price, 2) }}</p>
                            <form method="POST" action="{{ route('basket.store') }}">
                                @csrf
                                <input type="hidden" name="sku" value="{{ $product->sku }}">
                                <button type="submit" class="btn btn-outline-primary btn-sm">Add to Basket</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="h4 mb-3">Basket</h2>

        @if(!empty($baskets))
            <ul class="list-group mb-3">
                @foreach($baskets as $sku => $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $item['product_name'] }} x {{ $item['quantity'] }}
                    <span>${{ number_format($item['product_price'] * $item['quantity'], 2) }}</span>
                </li>
            @endforeach
            </ul>

            <div class="mb-4 p-2 text-bg-light text-end">
                <p><strong>Subtotal:</strong> ${{ number_format($subtotal, 2) }}</p>
                <p><strong>Delivery:</strong> ${{ number_format($delivery, 2) }}</p>
                <p class="fw-bold"><strong>Total:</strong> ${{ number_format($total, 2) }}</p>
            </div>
        @else
            <div class="alert alert-info">Your basket is empty.</div>
        @endif

    </div>
</x-app-layout>
