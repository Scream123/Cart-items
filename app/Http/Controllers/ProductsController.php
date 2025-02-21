<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Services\ProductService;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProductsController
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(): View
    {
        $products = Product::all();

        if (session()->has('user_id')) {
            $userId = session('user_id');
            $cartCount = $this->productService->getCartCount($userId, null);
        } else {
            $userSessionId = session('user_session_id');
            $cartCount = $this->productService->getCartCount(null, $userSessionId);
        }

        return view('products.index', compact('products'))->with('cartCount', $cartCount);
    }

    public function addToCart(AddToCartRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $productId = (int)$validated['product_id'];
        $quantityToAdd = (int)$validated['quantity'];

        if (session()->has('user_id')) {
            $userId = session('user_id');
            $response = $this->productService->addProductToCart($productId, $quantityToAdd, $userId, null);
        } else {
            $userSessionId = session()->get('user_session_id', uniqid('guest_', true));
            session(['user_session_id' => $userSessionId]);
            $response = $this->productService->addProductToCart($productId, $quantityToAdd, null, $userSessionId);
        }

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 400);
        }

        return response()->json([
            'message' => 'Product added to cart',
            'newCartCount' => $response['cartCount'],
        ]);
    }
}
