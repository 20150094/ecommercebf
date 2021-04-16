<?php

namespace App\Controller;
use App\Entity\User;
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
        $YOUR_DOMAIN = "https://www.djsem-electronic.com";
        $order= $entityManager
        ->getRepository(Order::class)
        ->findOneByReference(strval($reference));

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
        $product_for_stripe[]=[
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
        ];


        Stripe::setApiKey('sk_test_51IbTFlDtpGNDFtynjZWeBopVRugbMskXsB06YikqijydKVnLzs4kFQMlTaZwDpCDvp8tfGia66EGmBNSzdaLytvG00vV86wRed');


            $checkout_session = Session::create([
            'customer_email'=>$this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $product_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN .'/paypal/'.$order->getUser()->getFirstName().'-'.$order->getUser()->getLastName().'/'.$order->getReference().'/'.$order->getUser()->getEmail().'/'.$order->getUser()->getTelephone(),                // '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN .'/commande/erreur/{CHECKOUT_SESSION_ID}',
            ]);


        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();
        $response = new JsonResponse(['id' => $checkout_session->id]);

        return $response;
    }
}
