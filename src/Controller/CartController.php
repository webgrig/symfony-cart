<?php
namespace App\Controller;

use App\Cart\Cart;
use App\Cart\Id;
use App\Cart\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public $cart;
    public function __construct(
        Cart $cart
    )
    {
        $this->cart = $cart;
    }

    /**
     * @Route("/", name="cart")
     */
    public function index(): Response
    {

//        $product = new Product(Id::next(), 'sleeping bed', 300);
//        $productRepo = $this->getDoctrine()->getRepository(Product::class);
//        $productRepo->add($product);
//        $product2 = new Product(Id::next(), 'bike', 200);
//        $productRepo->add($product2);
//        $product3 = new Product(Id::next(), 'car', 3000);
//        $productRepo->add($product3);

        $product1Id = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['title' => 'sleeping bed'])->getId();
        $product2Id = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['title' => 'bike'])->getId();
//
//        $product3Id = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['title' => 'car'])->getId();
//

        $this->cart->addItem($product2Id, 20);
        $this->cart->addItem($product1Id, 4);
//        $this->cart->addItem($product2Id, 2);
//        $this->cart->addItem($product3Id, 3);
//        $this->cart->addItem($product2Id, 5);

        $this->cart->removeItem($product1Id);
//
//        $this->cart->clear();
//        $this->cart->removeItem($product2Id);
//        dd($this->cart);
        return $this->render('index.html.twig', ['cart' => $this->cart]);
    }

    /**
     * @param $id
     * @param $amount
     * @param $cost
     * @return string
     */
    public function add($id, $amount, $cost): string
    {
        $cart = $this->container->get('cart');
        $cart->add($id, $amount, $cost);
        return 'OK';
    }
}