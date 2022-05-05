<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonneController extends AbstractController
{

    #[Route('/personne', name: 'personne.index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository=$doctrine->getRepository(Personne::class);
        $personnes=$repository->findAll();
        return $this->render('personne/index.html.twig',['personnes'=>$personnes]);
    }

    #[Route('/personne/all', name: 'personne.index.all')]
    public function indexAll(ManagerRegistry $doctrine): Response
    {
        $repository=$doctrine->getRepository(Personne::class);
        $personnes=$repository->findBy(['name'=>'fendri'],['age'=>'ASC'],2,);
        return $this->render('personne/index.html.twig',['personnes'=>$personnes]);
    }

    #[Route('/personne/all/p/{page?1}/{nbre?10}', name: 'personne.index.all.p')]
    public function indexAllPage(ManagerRegistry $doctrine,$page,$nbre): Response
    {
        $repository=$doctrine->getRepository(Personne::class);
        $nbPersonne=$repository->count([]);
        $nbrePage=ceil(($nbPersonne/$nbre));
        $personnes=$repository->findBy([],[],$nbre,($page-1)*$nbre);
        return $this->render('personne/index.html.twig',['personnes'=>$personnes,'isPaginated'=>true]);
    }

    #[Route('/personne/{id?1}', name: 'personne.id')]
    public function detail(Personne $personne=null): Response
    {
        if(!$personne){
            $this->addFlash('error',"le personne nexiste pas");
            return $this->redirectToRoute('personne.index');
        }
        return $this->render('personne/details.html.twig',['personne'=>$personne]);
    }

    #[Route('/personne/add', name: 'app_personne_add')]
    public function addPersonne(ManagerRegistry $doctrine): Response
    {
        $entityManager=$doctrine->getManager();
        $personne2= new Personne();
        $personne2->setFirstname('ayoub');
        $personne2->setName('fendri');
        $personne2->setAge(15);

        //ajouter l'operation de personne dans ma transaction
        $entityManager->persist($personne2);

        //execute la transaction
        $entityManager->flush();

        return $this->render('personne/details.html.twig', [
            'personne' => $personne2,
        ]);
    }
}
