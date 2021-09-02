<?php

namespace App\Cart\Cost;

use Doctrine\Common\Collections\ArrayCollection;

class FourCost implements CalculatorInterface
{
    private $next;

    public function __construct(CalculatorInterface $next)
    {
        $this->next = $next;
    }

    public function getCost(?ArrayCollection $items)
    {
        $cost = $this->next->getCost($items);

        $k = 0;
        foreach ($items as $item) {
            if ($k % 4 === 3) {
                $cost -= $item->getCost() - 1;
            }
            $k++;
        }
        return $cost;
    }
}