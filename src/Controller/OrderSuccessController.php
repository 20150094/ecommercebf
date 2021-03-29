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
                $content="Bonjour ".$order->getUser()->getFirstname()."</br>votre commande référence N° ".$order->getReference()." a été validée avec sucess</br></br>";
                $mail->send($order->getUser()->getEmail(),$order->getUser()->getFirstname(),'Votre commande à été Valider',$content);

                $Receivernumber=strval ($order->getUser()->getTelephone());
                $sms="Bonjour; </br> Votre commande référence N° ".$order->getReference()." a été validée avec sucess</br>";
                $sms.="Et vous sera bientôt livrée. Vous pouvez suivre l'état d'avancement du traitement de cette dernière dans votre espaca";

                $Sms=new Sms();

                ($Sms->send($Receivernumber,$Sms));
           
            }
           
            
            return $this->render('order_success/index.html.twig',[
                'order'=>$order
            ]);
    }
}
