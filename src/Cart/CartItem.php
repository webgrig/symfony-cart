<?php

namespace App\Cart;

class CartItem
{
    private $product;

    private $amount;


    public function __construct(
        Product $product = null,
        int $amount
    )
    {
        $this->amount = $amount;
        $this->product = $product;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->getProduct()->getId();
    }

    /**
     * @return string
     */
    public function setId(): string
    {
        return $this->getProduct()->setId();
    }

    /**
     * @return Product
     */

    public function getProduct(): object
    {
        return $this->product;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->product->getCost() * $this->amount;
    }

}