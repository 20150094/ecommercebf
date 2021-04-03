<?php

namespace App\Controller;
use App\Classe\Sms;
use App\Entity\Order;
use App\Entity\User;
use App\Classe\Mail;
use App\Entity\Product;
use App\Classe\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class OrderSuccessController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_validate")
     */
    public function index(Cart $cart,$stripeSessionId): Response
    {

        $order=$this->entityManager
            ->getRepository(Order::class)
            ->findOneByStripeSessionId($stripeSessionId);
        
            if(!$order || $order->getUser()!=$this->getUser())
            {
                return $this->redirectToRoute('home');
            }

            if($order->getState()==0)
            {
                $cart->remove();
                $order->setState(1);
                $this->entityManager->flush();
           
                $mail=new Mail();
                $sms=new Sms();
                $content="Bonjour ".$order->getUser()->getFirstname()." votre commande référence N° ".$order->getReference()." a été validée avec sucess ";
                $mail->send($order->getUser()->getEmail(),$order->getUser()->getFirstname(),'Votre commande à été Valider',$content);


                $number='+33'.$this->getUser()->getTelephone();
                $sms->send($content,strval($number));



           
            }
           
            
            return $this->render('order_success/index.html.twig',[
                'order'=>$order
            ]);
    }
}
