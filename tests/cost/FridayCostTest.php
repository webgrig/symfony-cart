<?php

namespace Tests\Cost;

use App\Cart\Cost\DummyCost;
use App\Cart\Cost\FridayCost;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class FridayCostTest extends TestCase
{
    /**
     * @dataProvider getDays
     */
    public function testCost($date, $cost)
    {
        $calculator = new FridayCost(new DummyCost(100), 5, $date);
        $this->assertEquals($cost, $calculator->getCost(new ArrayCollection()));
    }

    public function getDays()
    {
        return [
            'Monday' => ['2016-04-18', 100],
            'Tuesday' => ['2016-04-19', 100],
            'Wednesday' => ['2016-04-20', 100],
            'Thursday' => ['2016-04-21', 100],
            'Friday' => ['2016-04-22', 95],
            'Saturday' => ['2016-04-23', 100],
            'Sunday' => ['2016-04-24', 100],
        ];
    }
} 