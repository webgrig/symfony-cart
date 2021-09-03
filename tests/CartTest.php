<?php

namespace Test\Cart;

use App\Cart\Cart;
use App\Cart\CartItem;
use App\Cart\Cost\SimpleCost;
use App\Cart\Id;
use App\Cart\Product;
use App\Cart\Storage\MemoryStorage;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class CartTest extends KernelTestCase
{
    private Cart $cart;

    public function setUp(): void
    {
//        $containter = $this->getContainer();
        $this->cart = new Cart();
        $this->cart->setStorage(new MemoryStorage());
        $this->cart->setCalculator(new SimpleCost());
        $this->cart->setItems(new ArrayCollection());
        parent::setUp();
    }

    public function testCreate()
    {
        $this->assertEquals(new ArrayCollection(), $this->cart->getItems());
    }

    public function testAdd()
    {
        $id = Id::next()->getValue();
        $items = new ArrayCollection([$id => new CartItem(new Product($id, 'булка', 5), 10)]);
        $this->cart->setItems($items);

        $items = $this->cart->getItems();

        $this->assertEquals(1, $items->count());
        $this->assertEquals($id, $items[$id]->getProduct()->getId());
        $this->assertEquals(10, $items[$id]->getAmount());
        $this->assertEquals(50, $items[$id]->getCost());
    }

    public function testAddExist()
    {
        $id1 = Id::next()->getValue();
        $this->cart->addItem($id1, 3, 'булка2', 2);

        $this->cart->addItem($id1, 5, 'булка3', 1);
        $items = $this->cart->getItems();
        $this->assertEquals(1, $items->count());
        $this->assertEquals(8, $items[0]->getAmount());
    }

    public function testRemove()
    {
        $id1 = Id::next()->getValue();
        $this->cart->addItem($id1, 3, 'булка2', 2.2);
        $this->cart->removeItem($id1);
        $this->assertEquals(new ArrayCollection(), $this->cart->getItems());
    }

    public function testClear()
    {

        $id1 = Id::next()->getValue();
        $this->cart->addItem($id1, 3, 'булка2', 2.2);
        $this->cart->clear();

        $this->assertEquals(new ArrayCollection(), $this->cart->getItems());
    }

    public function testCost()
    {
        $id1 = Id::next()->getValue();
        $this->cart->addItem($id1, 3, 'булка2', 10);
        $id2 = Id::next()->getValue();
        $this->cart->addItem($id2, 5, 'булка3', 20);
        $this->assertEquals(130, $this->cart->getCost());
    }
} 