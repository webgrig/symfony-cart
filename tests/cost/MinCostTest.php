<?php

namespace Tests\Cost;

use App\Cart\Cost\DummyCost;
use App\Cart\Cost\MinCost;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class MinCostTest extends TestCase
{
    public function testMin()
    {
        $calc = new MinCost(new DummyCost(100), new DummyCost(80), new DummyCost(90));
        $this->assertEquals(80, $calc->getCost(new ArrayCollection()));
    }
} 