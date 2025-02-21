<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Cart;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{

    public function getCartItems(?int $userId, ?string $userSessionId): Collection
    {
        return Cart::where(function ($query) use ($userId, $userSessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('user_session_id', $userSessionId);
            }
        })->get();
    }

    public function addProductToCart(?int $userId, ?string $userSessionId, int $productId, int $quantity): void
    {
        Cart::create([
            'user_id' => $userId,
            'user_session_id' => $userSessionId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ]);
    }

    public function getCartCount(?int $userId, ?string $userSessionId): string
    {
        $cartCount = Cart::where(function ($query) use ($userId, $userSessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('user_session_id', $userSessionId);
            }
        })->sum('quantity');

        return (string) $cartCount;
    }
}
