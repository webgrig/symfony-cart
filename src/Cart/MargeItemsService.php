<?php

namespace App\Cart;

use Doctrine\Common\Collections\ArrayCollection;

class MargeItemsService
{
    private ArrayCollection $first;
    private ArrayCollection $second;
    private array           $res = [];

    /**
     * @param ArrayCollection $elem
     * @return bool
     */
    private function isEmpty(ArrayCollection $elem): bool
    {
        return $elem->isEmpty();
    }

    /**
     * @param CartItem $item
     */
    private function addElem(CartItem $item): void
    {;
        $this->res[$this->getProductId($item)] = $item;
    }

    /**
     * @param ArrayCollection $first
     */
    private function initResArr(ArrayCollection $first): void
    {
        foreach ($first as $firstItem)
        {
            $this->addElem($firstItem);
        }
    }

    /**
     * @param ArrayCollection $first
     */

    private function parseFirst(ArrayCollection $first): void
    {
        foreach ($first as $firstItem)
        {
            $this->parseSecond($this->second, $firstItem);
        }
    }

    /**
     * @param ArrayCollection $second
     * @param CartItem $firstItem
     */

    private function parseSecond(ArrayCollection $second, CartItem $firstItem): void
    {
        foreach ($second as $secondItem)
        {
            if (!$this->checkEqualsElems($firstItem, $secondItem))
            {
                $this->addElem($secondItem);
                continue;
            }
            if (!$this->alreadyExistsInRes($secondItem))
            {
                $this->addElem($secondItem);
                continue;
            }

            $secondItem->setAmount($firstItem->getAmount() + $secondItem->getAmount());
            $this->addElem($secondItem);
        }
    }

    /**
     * @param CartItem $firstItem
     * @param CartItem $secondItem
     * @return bool
     */
    private function checkEqualsElems(CartItem $firstItem, CartItem $secondItem): bool
    {
        if ($this->isEquals($firstItem, $secondItem))
        {
            return true;
        }
        return false;
    }

    /**
     * @param CartItem $item
     * @return string
     */
    private function getProductId(CartItem $item): string
    {
        return $item->getProduct()->getId();
    }

    /**
     * @param CartItem $firstItem
     * @param CartItem $secondItem
     * @return bool
     */
    private function isEquals(CartItem $firstItem, CartItem $secondItem): bool
    {
        return $this->getProductId($firstItem) == $this->getProductId($secondItem);
    }

    /**
     * @param CartItem $item
     * @return bool
     */
    private function alreadyExistsInRes(CartItem $item): bool
    {
        return array_key_exists($this->getProductId($item), $this->res);
    }

    /**
     * @return ArrayCollection
     */
    public function margeItems(ArrayCollection $first, ArrayCollection $second): object
    {
        $this->first = $first;
        $this->second = $second;
        if ($this->isEmpty($this->first))
        {
            return $this->second;
        }
        $this->initResArr($this->first);
        $this->parseFirst($this->first);
        return new ArrayCollection(array_values($this->res));
    }

}