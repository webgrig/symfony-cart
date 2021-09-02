<?php

namespace Tests\Cost;

use App\Cart\Cost\BirthdayCost;
use App\Cart\Cost\DummyCost;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class BirthdayCostTest extends TestCase
{
    public function testActive()
    {
        $calc = new BirthdayCost(new DummyCost(100), 5, '1988-04-12', '2016-04-12');
        $this->assertEquals(95, $calc->getCost(new ArrayCollection()));
    }

    public function testNone()
    {
        $calc = new BirthdayCost(new DummyCost(100), 5, '1988-05-12', '2016-04-12');
        $this->assertEquals(100, $calc->getCost(new ArrayCollection()));
    }
} 