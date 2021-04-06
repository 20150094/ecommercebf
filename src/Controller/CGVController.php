<?php

namespace App\Controller;

use App\Classe\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CGVController extends AbstractController
{


    /**
     *@Route("/CGV", name="CGV")
     */
    public function index()
    {
        return $this->render('CGV/CGV.html.twig');

    }
}