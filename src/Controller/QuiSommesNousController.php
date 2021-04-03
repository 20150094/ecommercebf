<?php


namespace App\Controller;



use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuiSommesNousController extends AbstractController
{
    /**
     * @Route("/qui-somme-nous", name="where-you-are")
     */
    public function index()
    {
        return $this->render('contact/qui-sommes-nous.html.twig',[

        ]);

    }

}