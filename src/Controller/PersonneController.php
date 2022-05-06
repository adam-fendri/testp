<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        return $this->render('personne/index.html.twig',[
            'personnes'=>$personnes,
            'isPaginated'=>true,
            'nbrePage'=>$nbrePage,
            'nbre'=>$nbre,
            'page'=>$page
        ]);
    }

    #[
        Route('personne/alls/{page?1}/{nbre?12}', name: 'personne.list.alls')
    ]
    public function indexAlls(ManagerRegistry $doctrine, $page, $nbre): Response {
//        echo ($this->helper->sayCc());
        $repository = $doctrine->getRepository(Personne::class);
        $nbPersonne = $repository->count([]);
        // 24
        $nbrePage = ceil($nbPersonne / $nbre) ;

        $personnes = $repository->findBy([], [],$nbre, ($page - 1 ) * $nbre);
        //$listAllPersonneEvent = new ListAllPersonnesEvent(count($personnes));
        //$this->dispatcher->dispatch($listAllPersonneEvent, ListAllPersonnesEvent::LIST_ALL_PERSONNE_EVENT);

        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
            'isPaginated' => true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre
        ]);
    }

    #[Route('personne/alls/age/{ageMin}/{ageMax}', name: 'personne.list.age')]
    public function personnesByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response {

        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findPersonneByAgeInterval($ageMin, $ageMax);
        return $this->render('personne/index.html.twig', ['personnes' => $personnes]);
    }

    #[Route('personne/stats/age/{ageMin}/{ageMax}', name: 'personne.list.stats')]
    public function statsPersonnesByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response {

        $repository = $doctrine->getRepository(Personne::class);
        $stats = $repository->statsPersonneByAgeInterval($ageMin, $ageMax);
        return $this->render('personne/stats.html.twig', [
            'stats' => $stats[0],
            'ageMin'=> $ageMin,
            'ageMax'=> $ageMax
        ]);
    }



    #[Route('/personne/all/age/{ageMin?18}/{ageMax?45}/{page?1}/{nbre?10}', name: 'personne.personneByAge')]
    public function personneByAge(ManagerRegistry $doctrine,$page,$nbre,$ageMin,$ageMax): Response
    {
        $repository=$doctrine->getRepository(Personne::class);
        $nbPersonne=$repository->count([]);
        $nbrePage=ceil(($nbPersonne/$nbre));
        //$personnes=$repository->findBy([],[],$nbre,($page-1)*$nbre);
        $personnes=$repository->findPersonneByAgeInterval($ageMin,$ageMax);
        return $this->render('personne/index.html.twig',[
            'personnes'=>$personnes,
            'isPaginated'=>true,
            'nbrePage'=>$nbrePage,
            'nbre'=>$nbre,
            'page'=>$page
        ]);
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

    #[Route('/personne/delete/{id}', name: 'personne.delete')]
    public function delete(Personne $personne = null, ManagerRegistry $doctrine): RedirectResponse
    {
        //recuperer la personne
        if($personne){
            //si la personne existe => le supprimer et addFlash success
            $manager = $doctrine->getManager();
            // ajout de la supression dans la transaction
            $manager->remove($personne);
            //executer la transaction
            $manager->flush();
            //addFlash
            $this->addFlash('success',"la personne a ete supprimee");
        }else{
            //si la personne nexiste pas => addFlash danger/erreur
            $this->addFlash('error',"la personne nexiste pas");
        }
        return $this->redirectToRoute('personne.index.all.p');
    }

    #[Route('/personne/update/{id}/{firstname}/{name}/{age}', name: 'personne.update')]
    public function update(Personne $personne=null, ManagerRegistry $doctrine, $id, $firstname, $name, $age) :Response{
        //verif que personne existe
        if ($personne){
            //si existe => update +addFlash success
            $personne->setName($name);
            $personne->setFirstname($firstname);
            $personne->setAge($age);
            $entityManager=$doctrine->getManager();
            $entityManager->persist($personne);

            $entityManager->flush();
            //addFlash
            $this->addFlash('success',"la personne a ete maj");
        }else{
            //sinon => addFlash error
            $this->addFlash('error','la personne nexiste pas');
        }
        return $this->redirectToRoute('personne.index.all.p');





    }


}
