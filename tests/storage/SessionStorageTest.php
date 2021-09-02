<?php

namespace Tests\Storage;


use App\Cart\CartItem;
use App\Cart\Id;
use App\Cart\MargeItemsService;
use App\Cart\Product;
use App\Cart\Storage\SessionStorage;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;


class SessionStorageTest extends KernelTestCase
{
    private $session;
    private $storage;
    private $_em;
    public function setUp(): void
    {
        $containter = $this->getContainer();
        $_em = $containter->get('doctrine.orm.default_entity_manager');
        $this->_em = $_em;
        $session = new Session(new MockArraySessionStorage());
        $this->session = $session;
        $this->storage = new SessionStorage('cart', new MargeItemsService(), $this->session, $this->_em);
        parent::setUp();
    }


    public function testCreate()
    {

        $this->assertEquals(new ArrayCollection(), $this->storage->loadItems());
    }

    public function testStore()
    {
        $id = Id::next()->getValue();
        $product = new Product($id, 'scuba', 400);
        $item = new CartItem($product, 7);

        $id2 = $item->setId();

        $this->storage->save(new ArrayCollection([6 => $item]));
        $items = $this->storage->loadItems();
        $this->assertEquals(1, $items->count());
        $this->assertNotNull($items->get(6));
        $this->assertEquals($id2, $items->get(6)->getProduct()->getId());
        $this->assertEquals(7, $items->get(6)->getAmount());
        $this->assertEquals(2800, $items->get(6)->getCost());
    }
} 