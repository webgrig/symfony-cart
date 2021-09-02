<?php

namespace App\Cart\Cost;

use Doctrine\Common\Collections\ArrayCollection;

class MinCost implements CalculatorInterface
{
    /**
     * @var CalculatorInterface[]
     */
    private $calculators;

    public function __construct()
    {
        $calculators = func_get_args();
        foreach ($calculators as $calculator) {
            if (!$calculator instanceof CalculatorInterface) {
                throw new \InvalidArgumentException('Invalid calculator');
            }
        }
        $this->calculators = $calculators;
    }

    public function getCost(?ArrayCollection $items)
    {
        $costs = [];
        foreach ($this->calculators as $calculator) {
            $costs[] = $calculator->getCost($items);
        }
        return min($costs);
    }
}