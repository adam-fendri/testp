<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HeritageTwigController extends AbstractController
{
    #[Route('/heritage', name: 'app_heritage_twig')]
    public function index(): Response
    {
        return $this->render('heritage_twig/index.html.twig');
    }

    #[Route('/heritage/default', name: 'app_heritage_twigg')]
    public function default(): Response
    {
        return $this->render('heritage_twig/default.html.twig');
    }
}
