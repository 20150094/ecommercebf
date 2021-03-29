<?php

namespace App\Controller;
use App\Form\AdressType;
use App\Entity\Adresse;
use App\Classe\Cart;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
class AccountAdressController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/compte/adresses", name="account_adresses")
     */
    public function index(): Response
    {
       
        return $this->render('account/adress.html.twig');
    }

    
    /**
     * @Route("/compte/ajouter-une-adresse", name="account_adresse_add")
     * 
     */
    public function add(Cart $cart,Request $request): Response
    {
        $adresse=new Adresse();
        $form=$this->createForm(AdressType::class,$adresse);
      
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {

            $adresse->setUser($this->getUser());
            $doctrine=$this->getDoctrine()->getManager();
            $doctrine->persist($adresse);
            $doctrine->flush();
            if($cart->get())
            {
                 return $this->redirectToRoute('order');
            }
            else
            {
                 return $this->redirectToRoute('account_adresses');
            }
        }
        return $this->render('account/adress_form.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/compte/modifier-une-adresse/{id}", name="account_adresse_edit")
     */
    public function edit(Request $request,$id): Response
    {
        $adresse=$this->entityManager
        ->getRepository(Adresse::class)
        ->findOneById($id);
        if(!$adresse || $adresse->getUser()!=$this->getUser())
        {
            return $this->redirectToRoute('account_adresses');
        }
        $form=$this->createForm(AdressType::class,$adresse);
      
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {

            
            $doctrine=$this->getDoctrine()->getManager();
            
            $doctrine->flush();
            return $this->redirectToRoute('account_adresses');
         
            
        }
        return $this->render('account/adress_form.html.twig',[
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route("/compte/supprimer-une-adresse/{id}", name="account_adresse_delete")
     */
    public function delete($id)
    {
        $adresse=$this->entityManager
        ->getRepository(Adresse::class)
        ->findOneById($id);
        if($adresse && $adresse->getUser()==$this->getUser())
        {
            $this->entityManager->remove($adresse);
            $this->entityManager->flush();
        }
        

            
          
            
            
            return $this->redirectToRoute('account_adresses');
         
            
       
    }
}
