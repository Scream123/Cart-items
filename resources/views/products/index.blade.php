@extends('layouts.app')
@section('content')
    <h1 class="mb-4">List of products</h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Image</th>
            <th>Title</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                         style="width: 100px;">
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->price }} $</td>
                <td>
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" step="1"
                           id="quantity-{{ $product->id }}" style="width: 60px;">
                </td>
                <td>
                    <form action="{{ route('products.add', $product->id) }}" method="POST" class="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="input-quantity-{{ $product->id }}" value="1">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cart-plus"></i> Add to cart
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
