<?php
namespace App\Mapper\Contract;

use App\Entity\Order;

interface IDataMapper {
    /**
     * @param array $data 
     * @return Order 
     */
    public static function map(array $data): Order;
}