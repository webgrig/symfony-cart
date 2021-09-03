<?php

namespace App\Cart\Storage;

use App\Cart\Cart;
use App\Cart\MargeItemsService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionStorage implements StorageInterface
{
    private Session $session;
    private $sessionKey;
    private MargeItemsService $margeItemsService;
    private ArrayCollection $items;
    private EntityManagerInterface $_em;

    public function __construct(
        $sessionKey,
        SessionInterface $session,
        EntityManagerInterface $_em
    )
    {
        $this->session = $session;
        $this->sessionKey = $sessionKey;
        $this->margeItemsService = new MargeItemsService();
        $this->items = $this->loadItems();
        $this->_em = $_em;
    }

    public function loadItems(): ?object
    {
        $items = $this->session->get($this->sessionKey, new ArrayCollection());
        $this->items = $items;
        return $items;
    }

    public function getUser(): ?object
    {
        return null;
    }

    public function save(ArrayCollection $items): void
    {
        $this->session->set($this->sessionKey, $items);
        $this->items = $items;
    }

    /**
     * @return ArrayCollection
     */
    public function clear(): object
    {
        $this->session->remove($this->sessionKey);
        return $this->items = new ArrayCollection();
    }

    public function unSerialiseItems(array $items): object
    {
        return $this->_em->getRepository(Cart::class)->unSerialiseItems($items);
    }

    public function margeItems(ArrayCollection $first, ArrayCollection $second): object
    {
        return $this->margeItemsService->margeItems($first, $second);
    }


    public function addItem(string $id, int $amount, string $title = null, float $cost = null): ArrayCollection
    {
        $newItem = [$id=>$amount];
        $items = $this->margeItems($this->loadItems(), $this->unSerialiseItems($newItem));
        $this->save($items);
        return $items;
    }


}