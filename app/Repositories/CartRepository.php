<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\CartRepositoryInterface;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;

class CartRepository implements CartRepositoryInterface
{
    public function getCartItemsByUser($userIdOrSessionId): Collection
    {
        return Cart::where('user_id', $userIdOrSessionId)
            ->orWhere('user_session_id', $userIdOrSessionId)
            ->with('product')
            ->get();
    }

    public function getCartItemById(int $cartId, string $userIdOrSessionId): ?Cart
    {
        return Cart::where('id', $cartId)
            ->where(function ($query) use ($userIdOrSessionId) {
                $query->where('user_id', $userIdOrSessionId)
                    ->orWhere('user_session_id', $userIdOrSessionId);
            })
            ->first();
    }

    public function updateCartItemQuantity(Cart $cartItem, int $quantity): bool
    {
        return $cartItem->update(['quantity' => $quantity]);
    }

    public function deleteCartItem(Cart $cartItem): bool
    {
        return $cartItem->delete();
    }

    public function getCartCountBySession($userSessionId): string
    {
        return Cart::where('user_session_id', $userSessionId)->sum('quantity');
    }

    public function getCartTotalPrice(?int $userId, ?string $userSessionId): float
    {
        return Cart::where(function ($query) use ($userId, $userSessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('user_session_id', $userSessionId);
            }
        })
            ->with('product')
            ->get()
            ->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
    }
}
