<?php

namespace App\Controller;
use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request,UserPasswordEncoderInterface $encoder): Response
    {
        $notification=null;
        $user=new User();
        $form=$this->createForm(RegisterType::class,$user);
      

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $user = $form->getData();
            $search_email=$this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            
            if(!$search_email)
            {
                $password=$user->getPassword();
                $encoded = $encoder->encodePassword($user, $password);
                $user->setPassword($encoded);
            
                $doctrine=$this->getDoctrine()->getManager();

                $doctrine->persist($user);

                $doctrine->flush();
                $mail=new Mail();
                $content="Bonjour".' '.$user->getFirstname()."</br> Bienvenu la première plateforme dédié à l'électronique en Afrique</br></br>";
                $mail->send($user->getEmail(),$user->getFirstname(),'Bienvenu à DJSEM-ELECTRONICS',$content);
               
               
                $notification="Votre inscription s'est bien déroulée vous pouvez dès à présent vous connectez à votre compte";
                
           
            }
            else
            {
                $notification="l'email que vous avez renseigné existe déjà";
            }
            
            
        }
        return $this->render('register/index.html.twig',
        ['form'=>$form->createView(),
        'notification'=>$notification]);
    }
}
