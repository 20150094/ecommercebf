<?php

namespace App\Controller;
use App\Classe\Sms;
use App\Entity\User;
use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/mot-de-passe-oublie", name="reset_password")
     */
    public function index(Request $request): Response
    {

        if($this->getUser())
        {
            return $this->redirectToRoute('home');
        }

        if($request->get('email'))
        {
            $user= $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            if($user)
            {    // 1: Enregistrer en base la demande de reset_password avec user, token et createdAt
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTime());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();
                //2: Envoyer un email à l'utilisateur avec un ien lui permettant de mettre à jour son mot de passe
                $mail= new Mail();
                //$sms=new Sms();
                $url=$this->generateUrl('update_password',[
                    'token'=> $reset_password->getToken()
                    ],UrlGeneratorInterface::ABSOLUTE_URL);
                    
                $content="Bonjour ".$user->getFirstname()."<br/>Vous avez demandé à rénitialiser votre mot de passe sur le site DJSEM-ELECTRONICS.<br/></br>";
                $content.="Merci de bien vouloir cliquer sur le lien suivant valable 3h:  <a href='".$url."'> mettre à jour votre mot de passe</a>.";
                $mail->send($user->getEmail(),$user->getFirstname().' '.$user->getLastname(),'Rénitialiser votre mot de passe sur le site DJSEM-ELECTRONICS',$content);
                //$sms->send($content,'33761233467');
                $this->addFlash('notice',"vous allez recevoir dans quelques seconde un mail  contenant la  procédure pour Réinitialiser votre mot de passe. si ce n'est pas le cas vérifié dans vos spams.");
            }
            else
            {
                $this->addFlash('notice','Cette adresse email est inconnue.');
            }
        }
        return $this->render('reset_password/index.html.twig');
    }


    /**
     * @Route("/modifier-mon-mot-de-passe/{token}", name="update_password")
     */
    public function update($token,Request $request,UserPasswordEncoderInterface $encoder): Response
    {
        $reset_password=$this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
        if(!$reset_password)
        {
            return $this->redirectToRoute('reset_password');
        }
        //vérifier si le createdAt = now-3
        $now= new \DateTime();
        if($now > $reset_password->getCreatedAt()->modify('+3 hour'))
        {
           $this->addFlash('notice','Votre demande de mot de passe a expiré merci de la renouveler.');
           return $this->redirectToRoute('reset_password');
        }


        $form=$this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $new_pwd=$form->get('nouveau_password')->getData();
            $password = $encoder->encodePassword($reset_password->getUser(),$new_pwd);
            $reset_password->getUser()->setPassword($password);
            $this->entityManager->flush();

            $this->addFlash('notice','Votre mot de passe a bien été mit à jour');
        
            return $this->redirectToRoute('app_login');
        }  
          return $this->render('reset_password/update.html.twig',[
                'form'=>$form->createView()
            ]);

    }
}
