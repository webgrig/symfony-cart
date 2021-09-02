<?php

namespace Tests\Cost;

use App\Cart\Cost\BigCost;
use App\Cart\Cost\DummyCost;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class BigCostTest extends TestCase
{
    public function testActive()
    {
        $calculator = new BigCost(new DummyCost(1000), 500, 3);
        $this->assertEquals(970, $calculator->getCost(new ArrayCollection()));
    }

    public function testNone()
    {
        $calculator = new BigCost(new DummyCost(300), 500, 5);
        $this->assertEquals(300, $calculator->getCost(new ArrayCollection()));
    }
} 