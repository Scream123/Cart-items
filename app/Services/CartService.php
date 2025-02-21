<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CartRepository;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class CartService
{
    protected $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function getCartItems($userIdOrSessionId): Collection
    {
        return $this->cartRepository->getCartItemsByUser($userIdOrSessionId);
    }

    public function updateCartItem(int $cartId, int $quantity, $userId, $userSessionId): ?array
    {
        $cartItem = $this->cartRepository->getCartItemById($cartId, $userSessionId);

        if (!$cartItem) {
            return null;
        }

        $product = Product::findOrFail($cartItem->product_id);

        // Checking the availability of goods in the warehouse
        if ($product->stock < $quantity) {
            return ['error' => 'Not enough product in stock.'];
        }

        $this->cartRepository->updateCartItemQuantity($cartItem, $quantity);

        $newPrice = $product->price * $quantity;
        $newCartCount = $this->cartRepository->getCartCountBySession($userSessionId);
        $newCartTotalPrice = $this->cartRepository->getCartTotalPrice($userId, $userSessionId);

        return [
            'newPrice' => $newPrice,
            'newQuantity' => $quantity,
            'newCartCount' => $newCartCount,
            'newCartTotalPrice' => $newCartTotalPrice
        ];
    }

    public function removeCartItem(int $cartId, $userId,string $userSessionId,int $quantityToRemove): ?array
    {
        $cartItem = $this->cartRepository->getCartItemById($cartId, $userSessionId);

        if (!$cartItem) {
            return null;
        }

        $product = Product::findOrFail($cartItem->product_id);

        if ($cartItem->quantity > $quantityToRemove) {
            $cartItem->decrement('quantity', $quantityToRemove);
            $newItemPrice = $cartItem->quantity * $product->price;
            $newItemQuantity = $cartItem->quantity;
        } else {
            $this->cartRepository->deleteCartItem($cartItem);
            $newItemPrice = 0;
            $newItemQuantity = 0;
        }
        $newCartCount = $this->cartRepository->getCartCountBySession($userSessionId);
        $newCartTotalPrice = $this->cartRepository->getCartTotalPrice($userId, $userSessionId);

        return [
            'message' => 'The product has been deleted from the cart!',
            'newCartCount' => $newCartCount,
            'newItemQuantity' => $newItemQuantity,
            'newItemPrice' => $newItemPrice,
            'newCartTotalPrice' => $newCartTotalPrice,
        ];
    }

    public function getTotalCartPrice($userId, $userIdOrSessionId): float
    {
        $userId = null;
        $userSessionId = null;

        if (is_int($userIdOrSessionId)) {
            $userId = $userIdOrSessionId;
        } else {
            $userSessionId = $userIdOrSessionId;
        }

        return $this->cartRepository->getCartTotalPrice($userId, $userSessionId);
    }
}
