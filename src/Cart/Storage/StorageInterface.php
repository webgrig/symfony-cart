<?php

namespace App\Cart\Storage;

use App\Cart\Cost\CalculatorInterface;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

interface StorageInterface
{
    /**
     * @return object|null
     */
    public function loadItems(): ?object;

    /**
     * @return User|null
     */
    public function getUser(): ?object;

    /**
     * @param ArrayCollection $items
     * @param CalculatorInterface $calculator
     */
    public function save(ArrayCollection $items): void;

    /**
     * @return ArrayCollection
     */
    public function clear(): object;

    /**
     * @param array $items
     * @return ArrayCollection
     */
    public function unSerialiseItems(array $items): object;


    /**
     * @param ArrayCollection $first
     * @param ArrayCollection $second
     * @return ArrayCollection
     */
    public function margeItems(ArrayCollection $first, ArrayCollection $second): object;
}