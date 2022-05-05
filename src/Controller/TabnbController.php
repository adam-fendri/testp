<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TabnbController extends AbstractController
{
    #[Route('/tabnb/{nb?5}', name: 'app_tabnb')]
    public function index($nb): Response
    {
        $notes=[];
        for($i=0;$i<$nb;$i++){
            $notes[]=rand(0,20);
        }
        return $this->render('tabnb/index.html.twig', [
            'notes' => $notes,
        ]);
    }
}
