<?php

namespace App\Controller;

use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecondController extends AbstractController
{
  //  #[Route('/sayHello/{name}/{firstname}', name: 'sayHello')]
    public function sayHello($name, $firstname): Response
    {
        return $this->render('second/sayHello.html.twig', [
            'name'=>$name,
            'firstname'=>$firstname
        ]);
    }

    #[Route('/second', name: 'app_second')]
    public function index(): Response
    {
        return $this->render('second/index.html.twig', [
            'controller_name' => 'SecondController',
            'name'=>'Fendri',
            'firstname'=>'Adam'
        ]);
    }
}
