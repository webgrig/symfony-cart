<?php

namespace App\Cart\Cost;

use App\Cart\CartItem;
use Doctrine\Common\Collections\ArrayCollection;

interface CalculatorInterface
{
    /**
     * @param ArrayCollection|null $items
     * @return mixed
     */
    public function  getCost(?ArrayCollection $items);
} 