<?php

declare(strict_types=1);

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function getCartItems(?int $userId, ?string $userSessionId): Collection;

    public function addProductToCart(?int $userId, ?string $userSessionId, int $productId, int $quantity): void;

    public function getCartCount(?int $userId, ?string $userSessionId): string;
}
