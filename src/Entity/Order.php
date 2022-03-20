<?php
namespace App\Entity;

class Order {
    public function __construct(
        private int $id,
        private string $orderedAt,
        private array $orderItems,
    ) {}

    /**
     * @return int 
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string 
     */
    public function getOrderedAt(): string
    {
        // TODO to valid UTC ISO 8601 format
        return $this->orderedAt;
    }

    /**
     * @return array<OrderItem>
     */
    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    /**
     * @return int 
     */
    public function getUniqueProducts(): int
    {
        return count($this->orderItems);
    }

    /**
     * @return int 
     */
    public function getOrderedProducts(): int
    {
        return array_reduce($this->orderItems, fn(int $numbers, OrderItem $orderItem) => $numbers += $orderItem->getQuantity());
    }

    public function getAvgPrice(): float
    {
        $totalPrice = array_reduce($this->orderItems, fn(float $totalPrice, OrderItem $orderItem) => $totalPrice += $orderItem->getUnitPrice());

        return $totalPrice / $this->getUniqueProducts();
    }
}