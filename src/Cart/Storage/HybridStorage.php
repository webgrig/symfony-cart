<?php

namespace App\Cart\Storage;

use Doctrine\Common\Collections\ArrayCollection;

class HybridStorage implements StorageInterface
{
    private ?StorageInterface   $storage = null;


    public function __construct(
        StorageInterface    $from,
        StorageInterface    $to
    )
    {
        if(null == $this->getUser()) {
            $this->storage = $from;
            $items = $this->loadItems();
        }
        else {
            $this->storage = $to;
            $items = $this->margeItems($from->loadItems(), $this->loadItems());
            $from->clear();
        }
        $this->save($items);
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

    /**
     * @return ArrayCollection
     */
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
        return $this->storage->margeItems($first, $second);
    }

    public function addItem(string $id, int $amount, string $title = null, float $cost = null): object
    {
        return $this->storage->addItem($id, $amount);
    }
}