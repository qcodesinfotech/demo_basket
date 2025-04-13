<h1>Add New Product</h1>

<form method="POST" action="{{ route('products.store') }}">
    @csrf
    <label>SKU: <input type="text" name="sku" value="{{ old('sku') }}"></label><br>
    <label>Name: <input type="text" name="name" value="{{ old('name') }}"></label><br>
    <label>Price: <input type="text" name="price" value="{{ old('price') }}"></label><br>
    <button type="submit">Add Product</button>
</form>

@if($errors->any())
    <ul style="color: red;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<a href="{{ route('products.index') }}">Back to Products</a>
