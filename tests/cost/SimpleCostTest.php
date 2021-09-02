<?php

namespace Tests\Cost;


use App\Cart\Cart;
use App\Cart\Cost\SimpleCost;
use App\Cart\Id;
use App\Cart\Storage\MemoryStorage;
use PHPUnit\Framework\TestCase;

class SimpleCostTest extends TestCase
{
    private $cart;

    public function setUp(): void
    {
        $this->cart = new Cart();
        $this->cart->setStorage(new MemoryStorage());
        $this->cart->setCalculator(new SimpleCost());
        parent::setUp();
    }
    public function testCalculate()
    {
        $calculator = new SimpleCost();
        $id1 = Id::next()->getValue();
        $id2 = Id::next()->getValue();
        $id3 = Id::next()->getValue();
        $this->cart->addItem($id1, 5, 'shirt', 10);
        $this->cart->addItem($id2, 10, 'shirt', 20);
        $this->cart->addItem($id3, 50, 'shirt', 2);
        $this->assertEquals(350, $calculator->getCost($this->cart->getItems()));
    }
} 