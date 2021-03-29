<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Classe\Cart;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class CartController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/mon-panier", name="cart")
     */
    public function index(Cart $cart,Request $request): Response
    {
        if(count($cart->getfull())==0)
        {
            return $this->redirectToRoute('products');
        }
        
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getfull()
        ]);
    }
    
    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add(Cart $cart, $id)
    {
        $cart->add($id);
        
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/remove", name="remove_my_cart")
     */
    public function remove(Cart $cart)
    {
        $cart->remove();
        return $this->redirectToRoute('products');
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_to_cart")
     */
    public function delete(Cart $cart,$id)
    {
        $cart->delete($id);
        if($cart->get()==null)
        {
            return $this->redirectToRoute('products');
        }
       
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/decrease/{id}", name="decrease_to_cart")
     */
    public function decrease(Cart $cart,$id)
    {
        $cart->decrease($id);

        if($cart->get()==null)
        {
            return $this->redirectToRoute('products');
        }
        return $this->redirectToRoute('cart');
    }
}
