<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCartRequest;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): View
    {
        if (auth()->check()) {
            $userId = auth()->id();
            $cartItems = $this->cartService->getCartItems($userId);
            $cartCount = $cartItems->sum('quantity');
            $newCartTotalPrice = $this->cartService->getTotalCartPrice($userId, null);
        } else {
            $userSessionId = session('user_session_id');
            $cartItems = $this->cartService->getCartItems($userSessionId);
            $cartCount = $cartItems->sum('quantity');
            $newCartTotalPrice = $this->cartService->getTotalCartPrice(null, $userSessionId);
        }

        foreach ($cartItems as $item) {
            $item->newPrice = $item->product->price * $item->quantity;
        }

        return view('cart.index', compact('cartItems', 'cartCount', 'newCartTotalPrice'));
    }

    public function update(UpdateCartRequest $request, int $cartId): JsonResponse
    {
        $validated = $request->validated();

        $userId = auth()->check() ? auth()->id() : null;
        $userSessionId = !$userId ? session('user_session_id') : null;
        $response = $this->cartService->updateCartItem($cartId, (int) $validated['quantity'], $userId, $userSessionId);

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 400);
        }

        return response()->json([
            'message' => 'The quantity of goods in the basket has been updated!',
            'newPrice' => $response['newPrice'],
            'newQuantity' => $response['newQuantity'],
            'newCartCount' => $response['newCartCount'],
            'newCartTotalPrice' => $response['newCartTotalPrice'],
        ]);
    }

    public function removeCart(int $cartId): JsonResponse
    {
        $userId = auth()->check() ? auth()->id() : null;
        $userSessionId = !$userId ? session('user_session_id') : null;
        $quantityToRemove = (int)   request('quantity');
        $response = $this->cartService->removeCartItem($cartId, $userId, $userSessionId, $quantityToRemove);

        if ($response === null) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        return response()->json($response);
    }
}
