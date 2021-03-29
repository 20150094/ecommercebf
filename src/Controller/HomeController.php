<?php

namespace App\Controller;
use App\Classe\Mail;
use App\Classe\Sms;
use App\Entity\Product;
use App\Entity\Header;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;



class HomeController extends AbstractController
{

    
        private $entityManager;
    
        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->entityManager = $entityManager;
        }
        //php -S 127.0.0.1:8000 -t public

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {

        phpinfo();
        $products=$this->entityManager->getRepository(Product::class)->findByIsBest(1);
        $headers=$this->entityManager->getRepository(Header::class)->findAll();


        return $this->render('home/index.html.twig',[
            'products'=>$products,
            'headers'=>$headers
        ]);
    }
}
