<x-app-layout>
    <div class="container mt-4">
        <h1>Product List</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add Product</a>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <ul class="list-group">
            @foreach($products as $product)
                <li class="list-group-item">
                    {{ $product->name }} ({{ $product->sku }}) - ${{ number_format($product->price, 2) }}
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
