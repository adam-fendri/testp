<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{



    #[Route('/first', name: 'appfirst')]
    public function index(): Response
    {
        return $this->render('first/index.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }

    #[Route('/multi/{number1<\d+>}/{number2<\d+>}', name: 'multiplication')]
    public function multi($number1,$number2): Response
    {
        $res=$number1 * $number2;
        return $this->render('first/sayHello.html.twig', [
            'controller_name' => 'FirstController',
            'number1'=>$number1,
            'number2'=>$number2,
            'res'=>$res,
            'path'=>'    '
        ]);
    }

    #[Route('/template', name: 'app_first')]
    public function template(): Response
    {
        return $this->render('template.html.twig');
    }

}
