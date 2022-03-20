<?php
namespace App\Entity;

class Customer {
    public function __construct(private string $state) {}

    /**
     * @return string 
     */
    public function getState(): string
    {
        return $this->state;
    }
}