<?php

namespace App\Cart\Storage;

use App\Cart\CartItem;
use App\Cart\MargeItemsService;
use App\Cart\Product;
use Doctrine\Common\Collections\ArrayCollection;

class MemoryStorage implements StorageInterface
{

    private ArrayCollection         $items;
    private MargeItemsService      $margeItemsService;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->margeItemsService = new MargeItemsService();
    }

    public function loadItems(): ?object
    {
        return $this->items;
    }

    public function getUser(): ?object
    {
        return null;
    }

    /**
     * @param ArrayCollection $items
     */
    public function save(ArrayCollection $items): void
    {
        $this->items = $items;
    }

    /**
     * @return ArrayCollection
     */
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
        $first = null == $first ? new ArrayCollection() : $first;
        return $this->margeItemsService->margeItems($first, $second);
    }

    public function addItem(string $id, int $amount, string $title = null, float $cost = null): ArrayCollection
    {
        $item = [$id => new CartItem(new Product($id, $title, $cost), $amount)];
        $items = $this->margeItems($this->loadItems(), $this->unSerialiseItems($item));
        $this->save($items);
        return $items;
    }

}