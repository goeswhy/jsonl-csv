<?php
namespace App\Mapper;

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Mapper\Contract\IDataMapper;

class JsonlOrderDataMapper implements IDataMapper {
    public static function map(array $parsed): Order
    {
        $orderItems = array_map(function($orderItem) {
            return new OrderItem(
                quantity: (int) $orderItem['quantity'], 
                unitPrice: (float) $orderItem['unit_price']);
        }, $parsed['items']);
        $customer = new Customer(
            state: $parsed['customer']['shipping_address']['state']
        );

        return new Order(
            id: $parsed['order_id'],
            orderedAt: $parsed['order_date'],
            orderItems: $orderItems,
            customer: $customer
        );
    }
}