<?php

namespace App\Cart\Cost;

use Doctrine\Common\Collections\ArrayCollection;

class BigCost implements CalculatorInterface
{
    private $next;
    private $limit;
    private $percent;

    public function __construct(CalculatorInterface $next, $limit, $percent)
    {
        $this->next = $next;
        $this->limit = $limit;
        $this->percent = $percent;
    }

    public function getCost(?ArrayCollection $items)
    {
        $cost = $this->next->getCost($items);
        if ($cost > $this->limit) {
            return (1 - $this->percent / 100) * $cost;
        } else {
            return $cost;
        }
    }
}