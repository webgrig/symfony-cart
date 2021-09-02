<?php

namespace App\Cart;

use Doctrine\Common\Collections\ArrayCollection;

class MargeItemsService
{
    public function margeItems(ArrayCollection $first, ArrayCollection $second): object
    {
        if ($first->isEmpty())
        {
            return $second;
        }
        $res = [];
        foreach ($first as $firstItem)
        {
            $res[$firstItem->getProduct()->getId()] = $firstItem;
        }
        foreach ($first as $firstItem)
        {
            foreach ($second as $secondItem)
            {

                if ($firstItem->getProduct()->getId() !== $secondItem->getProduct()->getId())
                {
                    $res[$secondItem->getProduct()->getId()] = $secondItem;
                    continue;
                }
                if (!array_key_exists($secondItem->getProduct()->getId(), $res))
                {
                    $res[$secondItem->getProduct()->getId()] = $secondItem;
                    continue;
                }
                $secondItem->setAmount($firstItem->getAmount() + $secondItem->getAmount());
                $res[$secondItem->getProduct()->getId()] = $secondItem;
            }
        }
        return new ArrayCollection(array_values($res));
    }

}