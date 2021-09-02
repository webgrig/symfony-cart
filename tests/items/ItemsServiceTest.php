<?php

namespace Test\Cart;

use App\Cart\Cart;
use App\Cart\CartItem;
use App\Cart\CartItemService;
use App\Cart\Cost\SimpleCost;
use App\Cart\DummyProductRepo;
use App\Cart\Id;
use App\Cart\MargeItemsService;
use App\Cart\Product;
use App\Cart\Storage\MemoryStorage;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class ItemsServiceTest extends TestCase
{
    private $cart;

    public function setUp(): void
    {

        $this->cart = new Cart();
        $this->cart->setStorage(new MemoryStorage());
        $this->cart->setCalculator(new SimpleCost());
        parent::setUp();
    }
    public function testMargeItems()
    {
        $first = new ArrayCollection();
        $second = new ArrayCollection();
        $this->assertEquals(new ArrayCollection(), $this->cart->getStorage()->margeItems($first, $second));


        $id1 = Id::next()->getValue();
        $id2 = Id::next()->getValue();
        $id3 = Id::next()->getValue();

        $first = new ArrayCollection([
            0 => new CartItem( new Product($id1,'shirt', 10), 3),
            1 => new CartItem(new Product($id2, 'socks', 1), 10),
            2 => new CartItem(new Product($id3, 'jacket', 50), 2),
        ]);

        $second = new ArrayCollection([
            0 => new CartItem( new Product($id1,'shirt', 10), 5),
        ]);

        $result = new ArrayCollection([
            0 => new CartItem( new Product($id1,'shirt', 10), 8),
            1 => new CartItem(new Product($id2, 'socks', 1), 10),
            2 => new CartItem(new Product($id3, 'jacket', 50), 2),
        ]);
        $margeItems = new MargeItemsService();
        $this->assertEquals($result, $margeItems->margeItems($first, $second));
    }

//    public function testAdd()
//    {
//        $this->cart->addItem(1, 3);
//        $this->assertEquals(1, count($items = $this->cart->getItems()));
//        $this->assertEquals(1, $items[1]->getProduct()->getId());
//        $this->assertEquals(3, $items[1]->getAmount());
//        $this->assertEquals(900, $items[1]->getCost());
//    }
//
//    public function testAddExist()
//    {
//        $this->cart->addItem(1, 3);
//        $this->cart->addItem(1, 4);
//        $this->assertEquals(1, count($items = $this->cart->getItems()));
//        $this->assertEquals(7, $items[1]->getAmount());
//    }
//
//    public function testRemove()
//    {
//        $this->cart->addItem(2, 3);
//        $this->cart->removeItem(2);
//        $this->assertEquals([], $this->cart->getItems());
//    }
//
//    public function testClear()
//    {
//        $this->cart->addItem(2, 3);
//        $this->cart->clear();
//        $this->assertEquals([], $this->cart->getItems());
//    }
//
//    public function testCost()
//    {
//        $this->cart->addItem(1, 3);
//        $this->cart->addItem(2, 4);
//        $this->assertEquals(1700, $this->cart->getCost());
//    }
} 