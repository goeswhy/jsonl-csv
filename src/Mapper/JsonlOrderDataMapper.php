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
            return new OrderItem((int) $orderItem['quantity'], (float) $orderItem['unit_price']);
        }, $parsed['items']);
        $customer = new Customer($parsed['customer']['shipping_address']['state']);

        return new Order(
            $parsed['order_id'],
            $parsed['order_date'],
            $orderItems,
            $customer
        );
    }
}