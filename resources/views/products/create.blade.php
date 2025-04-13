<x-app-layout>
    <form method="POST" action="{{ route('products.store') }}" class="w-50 mx-auto">
        @csrf
        <div class="mb-3">
            <label for="sku" class="form-label">SKU:</label>
            <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku') }}">
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
        </div>
        
        <div class="mb-3">
            <label for="price" class="form-label">Price:</label>
            <input type="text" name="price" id="price" class="form-control" value="{{ old('price') }}">
        </div>
        
        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>

    @if($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Back to Products</a>
</x-app-layout>
