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

class PaypalController extends AbstractController
{
    /**
     * @Route("/paypal/{name}/{reference}/{email}/{telephone}", name="paypal")
     */
    public function index($name,$reference,$email,$telephone)
    {
        $mail = new Mail();
        $sms = new Sms();
        $content = "Bonjour " . $name . " votre commande référence N° " . $reference . " a été validée avec sucess ";
        $mail->send($email, $name, 'Votre commande à été Valider', $content);


        $number = '+33' . $telephone;
        $sms->send($content, strval($number));


        return $this->render('paypal/sucess.html.twig', [
            'name' => $name,
            'reference' => $reference,
            'email' => $email,
            'telephone' => $number
        ]);

    }


}