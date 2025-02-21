@extends('layouts.app')
@section('content')
    <h1>Cart</h1>

    @if($cartItems->isEmpty())
        <p>Cart is empty</p>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cartItems as $item)
                <tr id="cart-item-{{ $item->id }}">
                    <td>
                        <img src="{{ 'storage/' . $item->product->image }}" alt="{{ $item->product->name }}"
                             width="100">
                    </td>
                    <td>{{ $item->product->name }}</td>
                    <td>
                        <input type="number" class="quantity" value="{{ $item->quantity }}" min="1"
                               max="{{ $item->product->stock }}" step="1" style="width: 60px;"
                               data-cart-id="{{ $item->id }}">
                    </td>
                    <td id="item-price-{{ $item->id }}">{{ $item->newPrice }} $</td>
                    <td>
                        <button class="btn btn-warning update-btn" data-cart-id="{{ $item->id }}">Update</button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove-btn" data-cart-id="{{ $item->id }}">Delete
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div id="cart-summary">
            <p>Total items: <span id="total-items">{{ $cartCount }}</span></p>
            <p>Total price:<span id="total-price">{{ $newCartTotalPrice }}</span></p>
        </div>
    @endif
@endsection
