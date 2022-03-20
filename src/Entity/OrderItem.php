<?php
namespace App\Entity;

class OrderItem {
    public function __construct(
        private int $quantity,
        private float $unitPrice,
        private ?object $product = NULL,
    ) {}

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }
}