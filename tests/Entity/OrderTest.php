<?php

namespace App\Tests\Entity;

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\OrderItem;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    /**
     * @testdox It should have some summaries 
     */
    public function testInstanceMethods()
    {
        $instance = new Order(
            id: 10,
            orderedAt: 'Fri, 08 Mar 2019 12:13:29 +0000',
            orderItems: [
                new OrderItem(
                    quantity: 2,
                    unitPrice: 100.00,
                ),
                new OrderItem(
                    quantity: 3,
                    unitPrice: 150.00,
                ),
            ],
            customer: new Customer(state: 'VICTORIA'),
        );
        $this->assertEquals($instance->getAvgPrice(), 125);
        $this->assertEquals($instance->getTotalPrice(), 250);
        $this->assertEquals($instance->getOrderedProductsNumber(), 5);
        $this->assertEquals($instance->getUniqueProductsNumber(), 2);
        $this->assertEquals($instance->getCustomer()->getState(), 'VICTORIA');
    }
}
