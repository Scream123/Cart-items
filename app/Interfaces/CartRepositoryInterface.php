<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;

interface CartRepositoryInterface
{
    public function getCartItemsByUser(string $userIdOrSessionId): Collection;

    public function getCartItemById(int $cartId, string $userIdOrSessionId): ?Cart;

    public function updateCartItemQuantity(Cart $cartItem, int $quantity): bool;

    public function deleteCartItem(Cart $cartItem): bool;

    public function getCartCountBySession($userSessionId): string;

    public function getCartTotalPrice(?int $userId, ?string $userSessionId): float;

}

