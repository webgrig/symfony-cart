<?php

namespace App\Cart\Cost;

use Doctrine\Common\Collections\ArrayCollection;

class DummyCost implements CalculatorInterface
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getCost(?ArrayCollection $items)
    {
        return $this->value;
    }
}