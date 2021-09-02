<?php

namespace App\Cart;

use App\Cart\Cost\CalculatorInterface;
use App\Cart\Storage\StorageInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use SebastianBergmann\Complexity\Calculator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 * @ORM\Table(name="cart")
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\Column(type="json")
     */
    private                     $items = null;

    private ?StorageInterface   $storage = null;

    private ?CalculatorInterface $calculator;

    public function __construct(
        ?StorageInterface    $storage = null,
        ?CalculatorInterface $calculator = null
    )
    {
        $this->storage = $storage;
        $this->calculator = $calculator;
        $this->items = $this->loadItems();
        $this->user = $this->getUser();
        if ($this->user)
        {
            $this->id = $this->user->getId();
        }
    }

    /**
     * @return int|null
     */

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        $storage = $this->storage;
        return null == $storage ? null : $storage->getUser();
        return $this->user;
    }

    /**
     * @param User $user
     */

    public function setUser(User $user): object
    {
        return $this->user = $user;
    }

    /**
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param CalculatorInterface $calculator
     */

    public function setCalculator(CalculatorInterface $calculator): void
    {
        $this->calculator = $calculator;
    }

    /**
     * @param ArrayCollection $items
     * @return object
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this->items;
    }

    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param $id
     * @param $amount
     */
    public function addItem(string $id, int $amount, string $title = null, float $cost = null): void
    {
        $this->items = $this->storage->addItem($id, $amount, $title, $cost, $this->items);
    }

    /**
     * @param $id
     */
    public function removeItem($id): void
    {
        if ($key = $this->getProductId($id)){
            $this->items->removeElement($this->items->get($key));
        }
        else
        {
            throw new NotFoundHttpException('Товара #' . $id . ' нет корзине');
        }
        $this->save($this->items);
    }

    private function getProductId(string $id): ?string
    {
        foreach ($this->items as $key=>$item)
        {
            if ($item->getProduct()->getId() == $id)
            {
                return $key;
            }
        }

        return false;

    }

    private function save(ArrayCollection $items): void
    {
        $this->storage->save($items);
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->calculator->getCost($this->items);
    }

    /**
     * @return ArrayCollection|null
     */
    private function loadItems(): ?ArrayCollection
    {
        $storage = $this->storage;
        return $this->items = null == $storage ? null : $storage->loadItems();
    }

    public function clear(): void
    {
        $this->items = $this->storage->clear();
    }

    /**
     * @return StorageInterface
     */

    public function getStorage(): object
    {
        return $this->storage;
    }
}