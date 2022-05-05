<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Translation\t;

#[Route("/todo")]

class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session=$request->getSession();
        if(! $session->has('todos')){
            $todos=[
                'achat'=>'acheter du lait',
                'ranger'=>'ranger la chambre'
            ];
            $session->set('todos',$todos);
            $this->addFlash('info','la liste viens detre intialise');
        }
        return $this->render('todo/base.html.twig');
    }

    #[Route('/add/{name?test}/{content?content}',
        name:'todo.add'
    )]
    public function addTodo(Request $request,$name,$content): Response
    {
        $session=$request->getSession();
        if($session->has('todos')){
            $todos = $session->get('todos');
            if(isset($todos[$name])){
                $this->addFlash('error',"le todo avec id $name existe deja");
            }else{
                $todos[$name]=$content;
                $this->addFlash('success',"le todo avec id $name a ete creee avec success");
                $session->set('todos',$todos);
            }
        }
        else{
            $this->addFlash('error','la liste nest pas encore initialise');
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/update/{name}/{content}',name:'todo.update')]
    public function updateTodo(Request $request,$name,$content): Response
    {
        $session=$request->getSession();
        if($session->has('todos')){
            $todos = $session->get('todos');
            if(!isset($todos[$name])){
                $this->addFlash('error',"le todo avec id $name nexiste pas");
            }else{
                $todos[$name]=$content;
                $this->addFlash('success','le todo avec id $name a ete creee avec success');
                $session->set('todos',$todos);
            }
        }
        else{
            $this->addFlash('error','la liste nest pas encore initialise');
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/delete/{name}',name:'todo.delete')]
    public function deleteTodo(Request $request, $name){
        $session=$request->getSession();
        //test si session existe
        if($session->has('todos')){
            $todos=$session->get('todos');
            //name todo existe
            if(isset($todos[$name])){
                unset($todos[$name]);
                $session->set('todos',$todos);
                $this->addFlash('success',"le todo avec id $name a ete supprime");
            }//name todo nexiste pas
            else{
                $this->addFlash('error',"le todo avec id $name nexiste pas");
            }
        }
        else{
            $this->addFlash('error','la liste nest pas encore initialise');
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/reset',name:'todo.reset')]
    public function restTodo(Request $request)
    {
        $session = $request->getSession();
        $session->remove('todos');
        return $this->redirectToRoute('app_todo');
    }
}
