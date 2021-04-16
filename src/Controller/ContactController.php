<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends AbstractController
{
    /**
     * @Route("/nous-contacter", name="contact")
     */
    public function index(Request $request): Response
    {
        $form=$this->createForm(ContactType::class);
        $form->HandleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->addFlash('notice',"merci de nous avoir contacter, notre équipe va vous répondre dans les meilleurs délais");

            $mail=new Mail();

            $content="Bonjour vous avez recçu une nouvelle demande de :".$form->getData()['prenom']." ". $form->getData()['nom']."</br>";
            $content.=" contenu du message: "."<stong>".$form->getData()['content']."</stong></br>";
            $content.="Vous pouvez le reconctacter à l'adresse mail suivante:"."<stong>".$form->getData()['email']."</stong></br>";
    // à modifier l'adresse mail du destinataire
            $mail->send('contact@djsem-electronic.com',"Administrateur",'Nouvelle demande',$content);


            //$this->getUser()->getEmail()
        }
        return $this->render('contact/index.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}


