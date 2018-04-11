<?php
/**
 * Created by PhpStorm.
 * User: CHAIMA
 * Date: 11/04/2018
 * Time: 06:53
 */

namespace AdminBundle\Controller;


use BaseBundle\Entity\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EventsController extends Controller
{


    public function EventsAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $etat=1;
        $events=$em->getRepository(Event::class)->TrierEventsClient($etat);

        $msg='';
        //$notif=$this->get('mgilet.notification')->getAllUnseen();
      /*  if($request->isXmlHttpRequest()){
            foreach ($notif as $n)
            {
                $nn=$this->get('mgilet.notification')->setAsSeen($n);
                $nn->setSeen(1);
                $this->getDoctrine()->getManager()->persist($nn);
                $this->getDoctrine()->getManager()->flush();
            }
            $notif=$this->get('mgilet.notification')->getAllUnseen();
            $count=count($notif);
            return new JsonResponse($count);
        }
        $count=count($notif);*/

        return $this->render('AdminBundle:Events:Evenement.html.twig',array('events'=>$events));

    }
    public function adminAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $produit = $em
            ->getRepository('BaseBundle:Event')->findAll();


        $paginator = $this->get('knp_paginator');
        $produit = $paginator->paginate(
            $produit,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5)
        );
        return $this->render('AdminBundle:Events:afficherEvent.html.twig', array(
            'produits' => $produit
//            'okk' => $okk
        ));
}
}
