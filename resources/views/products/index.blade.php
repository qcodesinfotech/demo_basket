<h1>Product List</h1>

<a href="{{ route('products.create') }}">Add Product</a>

@if(session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif

<ul>
    @foreach($products as $product)
        <li>{{ $product->name }} ({{ $product->sku }}) - ${{ number_format($product->price, 2) }}</li>
    @endforeach
</ul>
