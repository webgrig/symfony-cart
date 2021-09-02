<?php

namespace App\Cart\Storage;

use App\Cart\Cost\CalculatorInterface;
use Doctrine\Common\Collections\ArrayCollection;

class HybridStorage implements StorageInterface
{
    private ?StorageInterface   $storage = null;
    private ArrayCollection     $items;
    private CalculatorInterface $calculator;


    public function __construct(
        StorageInterface    $from,
        StorageInterface    $to,
        CalculatorInterface $calculator
    )
    {
        $this->calculator = $calculator;
        if(null == $this->getUser()) {
            $this->storage = $from;
            $this->items = $from->loadItems();
        }
        else {
            $this->storage = $to;
            $this->items = $this->margeItems($from->loadItems(), $to->loadItems());
            $this->save($this->items);
            $from->clear();
        }
    }

    public function loadItems(): ?object
    {
        return $this->storage->loadItems();
    }

    public function getUser(): ?object
    {
        return null == $this->storage ? null : $this->storage->getUser();
    }

    public function save(ArrayCollection $items): void
    {
        $this->storage->save($items);
    }

    public function clear(): object
    {
        return $this->storage->clear();
    }

    public function unSerialiseItems(array $items): object
    {
        return $this->storage->unSerialiseItems($items);
    }

    public function margeItems(ArrayCollection $first, ArrayCollection $second): object
    {
        $items = $this->storage->margeItems($first, $second);
        return $items;
    }

    public function addItem(string $id, int $amount): ArrayCollection
    {
        $newItem = [$id=>$amount];
        $items = $this->margeItems($this->loadItems(), $this->unSerialiseItems($newItem));
        $this->save($items);
        return $items;
    }
}