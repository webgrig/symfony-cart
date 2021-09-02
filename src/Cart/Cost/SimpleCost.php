<?php

namespace App\Cart\Cost;

use Doctrine\Common\Collections\ArrayCollection;

class SimpleCost implements CalculatorInterface
{
    public function getCost(?ArrayCollection $items)
    {
        $cost = 0;
        if ($items){
            foreach ($items as $item) {
                $cost += $item->getCost();
            }
        }

        return $cost;
    }
}