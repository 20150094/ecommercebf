<?php

namespace App\Controller;
use Stripe\Stripe;
use App\Entity\Order;
use App\Entity\Product;
use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Classe\Cart;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
class StripeController extends AbstractController
{
    /**
     *@Route("/commande/create-session/{reference}", name="stripe_create-session")
     */
    public function index(EntityManagerInterface $entityManager,Cart $cart,$reference): Response
    {

        $product_for_stripe=[];
        $YOUR_DOMAIN = "https://djsem-electronic.com";
        $order= $entityManager
        ->getRepository(Order::class)
        ->findOneByReference($reference);

        if(!$order)
        {
            new JsonResponse(['error' => 'order']);
        }

        foreach($order->getOrderDetails()->getValues() as $product)
        {   $product_object=$entityManager
            ->getRepository(Product::class)
            ->findOneByName($product->getProduct());
            $product_for_stripe[]=[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN."/Images/".$product_object->getIllustration()],
                    ],
                    ],
                    'quantity' => $product->getQuantity(),
                ];

        }



        Stripe::setApiKey('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');




            $checkout_session = Session::create([
            'customer_email'=>$this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $product_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
            ]);


        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();
        $response = new JsonResponse(['id' => $checkout_session->id]);

        return $response;
    }
}
