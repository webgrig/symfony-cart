<?php

namespace App\Cart;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\Table(name="warehouse")
 */

class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="product_id", name="product_id")
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $title;

    /**
     * @ORM\Column(type="float", nullable=false)
     */

    private $cost;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @ORM\Column(name="warehouse_amount", type="smallint")
     */

    private $warehouseAmount;

    public function __construct(string $id, string $title, float $cost)
    {
        $this->id = $id;
        $this->title = $title;
        $this->cost = $cost;
    }

    /**
     * @return string
     */
    public function setId(): string
    {
        return $this->id = Id::next()->getValue();
    }
    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $tile): void
    {
        $this->title = $tile;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }
    public function setCost(float $cost): void
    {
        $this->cost = $cost;
    }

    public function getWarehouseAmount(): ?int
    {
        return $this->warehouseAmount;
    }
    public function setWarehouseAmount(int $warehouseAmount): void
    {
        $this->warehouseAmount = $warehouseAmount;
    }
}