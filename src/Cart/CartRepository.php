<?php

namespace App\Cart;


use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

/**
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    private Security $security;

    public function __construct(
        ManagerRegistry $registry,
        Security        $security
    )
    {
        $this->security = $security;
        parent::__construct($registry, Cart::class);
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        $user = $this->security->getUser();
        return null == $user ? null : $user;

    }

    public function add(ArrayCollection $items): void
    {
        if (!$cart = $this->findOneBy(['user' => $this->getUser()->getId()]))
        {
            $cart = new Cart();
        }
        $cart->setItems($items);

        $this->_em->persist($cart);

        $this->_em->flush();
    }

    public function clearCart(User $user)
    {
        $cart = $this->findOneBy(['user' => $this->getUser()->getId()]);
        $cart->setItems(new ArrayCollection());
        $this->_em->persist($cart);
        $this->_em->flush();
    }

    public function serialiseItems(ArrayCollection $items): array
    {
        $res = [];
        foreach ($items as $item)
        {
            $res[$item->getProduct()->getId()] = $item->getAmount();

        }
        return $res;

    }

    public function unSerialiseItems(array $items): object
    {
        $res = new ArrayCollection();

        foreach ($items as $key => $val)
        {

            $product = $this->_em->getRepository(Product::class)->findOneBy(['id' => $key]);
            if (!$product)
            {
                throw new NotFoundHttpException('Товар не найден.');
            }
            $res->add(new CartItem($product, $val));
        }
        return $res;
    }
}
