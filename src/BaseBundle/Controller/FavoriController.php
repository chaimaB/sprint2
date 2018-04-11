<?php
/**
 * Created by PhpStorm.
 * User: CHAIMA
 * Date: 10/04/2018
 * Time: 15:36
 */

namespace BaseBundle\Controller;


use BaseBundle\Entity\Event;
use BaseBundle\Entity\Favori;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FavoriController extends Controller
{
    public function ajouterFavAction(Request $request){
        $event=new Event();
        $event = $this->getDoctrine()->getManager();
        $Favori= $event->getRepository("BaseBundle:Favori")->findAll();

        $em= $this->getDoctrine()->getManager();
        $user=$this->getUser();
        // $idu=$user->getId();

        $id=$request->get('idEvent');

        $Event=$em->getRepository('BaseBundle:Event')->find($id);
        $favori=$em->getRepository('BaseBundle:Favori')->findOneBy(array('user'=>$user,'event'=>$id));

        if(empty($favori)){
            ?><script>alert('Cet évènement est dans votre favoris ^_^');</script><?php
            $favori=new Favori();
            $favori-> setUser($user);
            $favori-> setEvent($Event);
            $em->persist($favori);
            $em->persist($Event);
            $em->flush();
            $em=$this->getDoctrine()->getManager();


            /*return $this->render('BaseBundle:Favori:favori.html.twig',array(
                    'event'=>$Event
            ));*/
            $etat =1;
            $events= $event->getRepository("BaseBundle:Event")->TrieEvents($etat);
            $paginator=$this->get('knp_paginator');
            $events=$paginator->paginate(
                $events,
                $request->query->getInt('page',1),
                $request->query->getInt('page',4)
            );
            return $this->render('BaseBundle:Events:afficherEvent.html.twig',array(
                'event'=>$events,
                'favori'=>$Favori
            ));

        }
        ?><script>alert('Cet evenement est retirer de votre favoris ^_^');</script><?php

        /* $msg  =  'Vous avez déjà annuler votre participation!';
         $em=$this->getDoctrine()->getManager();
         $em->remove($participation);
         $Event->setNbreparticipation($Event->getNbreparticipation() - 1);
         $em->persist($Event);
         $em->flush();
         $etat=1;
         $events=$em->getRepository(Events::class)->TrierEventsClient($etat);*/
        /*return $this->render('BaseBundle:Favori:favori.html.twig',array(
            'event'=>$Event
        ));*/

        $em=$this->getDoctrine()->getManager();
        $em->remove($favori);
        $etat = 1;
        $em->flush();
        $events= $event->getRepository("BaseBundle:Event")->TrieEvents($etat);
        $paginator=$this->get('knp_paginator');
        $events=$paginator->paginate(
            $events,
            $request->query->getInt('page',1),
            $request->query->getInt('page',4)
        );
        return $this->render('BaseBundle:Events:afficherEvent.html.twig',array(

            'event'=>$events
        ));


    }

    public function MesFavoriesAction(Request $request){
        $em=$this->getDoctrine()->getManager();

        $user=$this->getUser();
        $favori=$em->getRepository(Favori::class)->findAll();
        //$idF=$favori->getEvent();
        $event=$em->getRepository(Event::class)->findAll();
            return $this->render('BaseBundle:Favori:favori.html.twig',array(
                'event'=>$event,
                'favori'=>$favori,
                'user'=>$user
            ));

    }
}