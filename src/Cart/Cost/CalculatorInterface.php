<?php

namespace App\Cart\Cost;

use Doctrine\Common\Collections\ArrayCollection;

interface CalculatorInterface
{
    /**
     * @param ArrayCollection|null $items
     * @return mixed
     */
    public function  getCost(?ArrayCollection $items);
} 