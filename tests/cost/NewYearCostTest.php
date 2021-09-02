<?php

namespace Tests\Cost;

use App\Cart\Cost\DummyCost;
use App\Cart\Cost\NewYearCost;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class NewYearCostTest extends TestCase
{
    public function testActive()
    {
        $calculator = new NewYearCost(new DummyCost(1000), 12, 5);
        $this->assertEquals(950, $calculator->getCost(new ArrayCollection()));
    }

    public function testNone()
    {
        $calculator = new NewYearCost(new DummyCost(1000), 6, 5);
        $this->assertEquals(1000, $calculator->getCost(new ArrayCollection()));
    }
} 