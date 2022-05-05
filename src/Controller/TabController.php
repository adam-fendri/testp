<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TabController extends AbstractController
{
    #[Route('/tab/nb/{nb?5}', name: 'appTab')]
    public function index($nb): Response
    {

        $notes=[];
        for($i=0;$i<$nb;$i++){
            $notes[]=rand(0,20);
        }
        return $this->render('tab/index.html.twig', [
            'notes' =>$notes
         ]);
    }

    #[Route('/tab/users', name: 'app_tab')]
    public function users(): Response
    {
        $users=[
            [ 'firstname'=>'adam', 'name'=>'fendri', 'age'=>'21' ],
            [ 'firstname'=>'imen', 'name'=>'fendri', 'age'=>'18' ],
            [ 'firstname'=>'ayoub', 'name'=>'fendri', 'age'=>'15' ]
        ];
        return $this->render('tab/users.html.twig', [
            'users' =>$users
        ]);
    }

}
