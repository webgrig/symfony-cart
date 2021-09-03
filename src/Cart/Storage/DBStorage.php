<?php

namespace App\Cart\Storage;

use App\Cart\Cart;
use App\Cart\CartRepository;
use App\Cart\MargeItemsService;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class DBStorage implements StorageInterface
{
    private                        $items;
    private MargeItemsService      $margeItemsService;
    private ?User                  $user = null;
    private CartRepository         $cartRepo;
    private EntityManagerInterface $_em;

    public function __construct(
        EntityManagerInterface  $_em
    )
    {
        $this->margeItemsService = new MargeItemsService();
        $this->_em = $_em;
        $this->cartRepo = $this->_em->getRepository(Cart::class);
        $this->user = $this->cartRepo->getUser();
    }

    public function loadItems(): ?object
    {
        $items = $this->cartRepo->findOneBy(['user'=>$this->user->getId()])->getItems();
        if (gettype($items) == 'array')
        {
            $items = $this->cartRepo->unSerialiseItems($items);
        }
        $this->items = $items;

        return $this->items;
    }

    public function getUser(): ?object
    {
        return $this->user;
    }

    public function save(ArrayCollection $items): void
    {
        $this->cartRepo->add($items);
    }

    /**
     * @return ArrayCollection
     */
    public function clear(): object
    {
        $this->cartRepo->clearCart($this->user);
        return $this->items = new ArrayCollection();
    }


    public function unSerialiseItems(array $items): object
    {
        return $this->cartRepo->unSerialiseItems($items);
    }

    public function margeItems(ArrayCollection $first, ArrayCollection $second): object
    {
        return $this->margeItemsService->margeItems($first, $second);
    }

    public function addItem(string $id, int $amount, string $title = null, float $cost = null): object
    {
        $newItem = [$id=>$amount];
        $items = $this->margeItems($this->loadItems(), $this->unSerialiseItems($newItem));
        $this->save($items);
        return $items;
    }

}