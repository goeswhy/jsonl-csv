<?php
namespace App\Entity;

class Order {
    public function __construct(
        private int $id,
        private string $orderedAt,
        private array $orderItems,
        private Customer $customer,
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
    public function getUniqueProductsNumber(): int
    {
        return count($this->orderItems);
    }

    /**
     * @return int 
     */
    public function getOrderedProductsNumber(): int
    {
        return array_reduce($this->orderItems, fn($numbers = 0, OrderItem $orderItem) => $numbers += $orderItem->getQuantity());
    }

    public function getAvgPrice(): float
    {
        return $this->getTotalPrice() / $this->getUniqueProductsNumber();
    }

    public function getTotalPrice(): float
    {
        return array_reduce($this->orderItems, fn($totalPrice = 0, OrderItem $orderItem) => $totalPrice += $orderItem->getUnitPrice());
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }
}