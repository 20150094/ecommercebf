<?php

namespace App\Controller;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/compte/modifier-mon-mot-de-passe", name="account_password")
     */
    public function index(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $user=$this->getUser();
        
        $form=$this->createForm(ChangePasswordType::class,$user);
        $notification=null;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $ancien_password=$form->get('ancien_password')->getData();
            if($encoder->isPasswordValid($user,$ancien_password))
            {
                $nouveau_password=$form->get('nouveau_password')->getData();
                $encoded = $encoder->encodePassword($user,$nouveau_password);
                $user->setPassword($encoded);
                $doctrine=$this->getDoctrine()->getManager();
                $doctrine->flush();
                $notification="votre mot de passe a bien été mis à jour";
            }
            else{
                $notification="votre mot de passe n'est pas le bon";

            }
           
        }
    
        return $this->render('account/password.html.twig',
        ['form'=>$form->createView(),
        'notification'=>$notification]);
    }
}
