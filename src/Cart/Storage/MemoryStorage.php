<?php

namespace App\Cart\Storage;

use App\Cart\Cart;
use App\Cart\CartItem;
use App\Cart\Cost\CalculatorInterface;
use App\Cart\MargeItemsService;
use App\Cart\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class MemoryStorage implements StorageInterface
{

    private ArrayCollection         $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function loadItems(): ?object
    {
        return $this->items;
    }

    public function getUser(): ?object
    {
        return null;
    }

    public function save(ArrayCollection $items): void
    {
        $this->items = $items;
    }

    public function clear(): object
    {
        return $this->items = new ArrayCollection();
    }

    public function unSerialiseItems(array $items): object
    {
        return new ArrayCollection($items);
    }

    public function margeItems(ArrayCollection $first, ArrayCollection $second): object
    {
        $margeItems = new MargeItemsService();
        return $margeItems->margeItems($first, $second);
    }

    public function addItem(string $id, int $amount, string $title, float $cost, ArrayCollection $items = null): ArrayCollection
    {
        $items = null == $items ? new ArrayCollection() : $items;
        $item = [$id => new CartItem(new Product($id, $title, $cost), $amount)];
        $items = $this->margeItems($items, $this->unSerialiseItems($item));
        $this->save($items);
        return $items;
    }

}