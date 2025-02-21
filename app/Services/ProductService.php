<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function addProductToCart(int $productId, int $quantity, ?int $userId, ?string $userSessionId): array
    {
        $product = Product::find($productId);

        if ($quantity > $product->stock) {
            return ['error' => 'Not enough product in stock'];
        }

        $cartItem = $this->productRepository->getCartItems($userId, $userSessionId)->firstWhere('product_id', $productId);

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;

            if ($newQuantity > $product->stock) {
                return ['error' => 'Not enough product in stock for this addition'];
            }

            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            $this->productRepository->addProductToCart($userId, $userSessionId, $productId, $quantity);
        }

        $cartCount = $this->productRepository->getCartCount($userId, $userSessionId);

        return ['cartCount' => $cartCount];
    }

    public function getCartCount(?int $userId, ?string $userSessionId): string
    {
        return $this->productRepository->getCartCount($userId, $userSessionId);
    }
}

