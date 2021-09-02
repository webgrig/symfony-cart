<?php

namespace App\Cart;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CartEntityListener
{
    private EntityManagerInterface $_em;

    public function __construct(EntityManagerInterface $_em)
    {
        $this->_em = $_em;
    }

    public function preUpdate(Cart $cart, LifecycleEventArgs $event)
    {
        $cart->setItems($this->_em->getRepository(Cart::class)->serialiseItems($cart->getItems()));
    }

    public function postLoad(Cart $cart, LifecycleEventArgs $event)
    {

        $cart->setItems($this->_em->getRepository(Cart::class)->unSerialiseItems($cart->getItems()));
    }
}