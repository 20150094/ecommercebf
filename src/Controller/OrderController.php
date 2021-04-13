<?php

namespace App\Controller;
use App\Form\OrderType;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Orderdetails;
use App\Classe\Cart ;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\DateTimeValidator;



class OrderController extends AbstractController
{



    
    
    private $entityManager;

    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
       
    }
    /**
     *@Route("/commande", name="order")
     */
    public function index(Cart $cart,Request $request): Response
    {
        
        if(!$this->getUser()->getAdresses()->getValues())
        {
            return $this->redirectToRoute('account_adresse_add');
        }
        $form=$this->createForm(OrderType::class,null,[
            'user'=>$this->getUser()
        ]);
        return $this->render('order/index.html.twig',[
            'form'=>$form->createView(),
            'cart'=>$cart->getFull(),
        ]);
   
   
   
    }


    /**
     *@Route("/commande/recapitulatif", name="order_recap",methods={"POST"})
     */
    public function add(Cart $cart,Request $request): Response
    {
        
        $date = new \DateTime();
     

        $form=$this->createForm(OrderType::class,null,[
            'user'=>$this->getUser()
        ]);
        

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {   
           
            $carriers=$form->get('carrier')->getData();
            
            $delivery=$form->get('adressess')->getData();
            
            $delivery_content=$delivery->getFirstName().' '.$delivery->getLastName();
            $delivery_content.='<br/>'.$delivery->getPhone();
            if($delivery)
            {
                 $delivery_content.='<br/>'.$delivery->getCompany();
            }
            $delivery_content.='<br/>'.$delivery->getAddress();
            $delivery_content.='<br/>'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content.='<br/>'.$delivery->getPays();

            
            $order=new Order();
            $reference=$date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrix());
            $order->setDelivery($delivery_content);
            $order->setState(0);
           
            
            $this->entityManager->persist($order);
            
           
            foreach($cart->getFull() as $product)
            {

             $orderDetails=new Orderdetails();
             $orderDetails->setMyOrder($order);
             $orderDetails->setProduct($product['product']->getName());
             $orderDetails->setQuantity($product['quantity']);
             $orderDetails->setPrice($product['product']->getPrix());
             $orderDetails->setTotal($product['product']->getPrix()*$product['quantity']);
             $this->entityManager->persist($orderDetails);

           
            }

            
           // $this->entityManager->flush();
            //$order=  $this->entityManager
               // ->getRepository(Order::class)
               // ->findOneByReference($reference);



            //dd($order->getOrderDetails());
           // die();
            return $this->render('order/add.html.twig',[
                'cart'=>$cart->getFull(),
                'carrier'=>$carriers,
                'delivery'=>$delivery_content,
                'reference'=>$order->getReference(),
                'name'=>$order->getUser()->getFullName(),
                'email'=>$order->getUser()->getEmail(),
                'telephone'=>$order->getUser()->getTelephone()

            ]);
                
        }
       
        return $this->redirectToRoute('cart');
   
   
    }
}
