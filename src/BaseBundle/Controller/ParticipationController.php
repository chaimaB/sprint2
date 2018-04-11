<?php
/**
 * Created by PhpStorm.
 * User: CHAIMA
 * Date: 03/04/2018
 * Time: 22:58
 */

namespace BaseBundle\Controller;

use BaseBundle\Entity\Event;
use BaseBundle\Entity\Participation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ParticipationController  extends Controller
{


    public function ParticiperAction(Request $request){

        $event=new Event();
        $event = $this->getDoctrine()->getManager();
        $Events= $event->getRepository("BaseBundle:Event")->findAll();

        $em= $this->getDoctrine()->getManager();
        $user=$this->getUser();
       // $idu=$user->getId();

        $id=$request->get('idEvent');

        $Event=$em->getRepository('BaseBundle:Event')->find($id);
        $participation=$em->getRepository('BaseBundle:Participation')->findOneBy(array('user'=>$user,'event'=>$id));
        $datej=new \DateTime('now');
        $week=date("Y-m-d", strtotime("-1 week"));
        $query = $em->createQuery(
            'SELECT e
             FROM BaseBundle:Event e
             WHERE e.date >=:d1 '
        );
        $query->setParameter('d1',$week);

        $events = $query->getResult();

        if(empty($participation)){

            $participation=new Participation();
            $Event->setParticip($Event->getParticip() + 1);
            $Event->setTicket($Event->getTicket() - 1);
            $participation-> setUser($user);
            $participation-> setEvent($Event);
            $em->persist($participation);
            $em->persist($Event);
            $em->flush();
            $em=$this->getDoctrine()->getManager();
            $etat=1;
            $Events=$em->getRepository(Event::class)->TrieEvents($etat);
            $idu=$Event->getUser();
            $nom=$Event->getNom();
            $date=$Event->getDate();
            $image=$Event->getImage();
            $description=$Event->getDescription();
            if(empty($participation) )
            {
                if ($idu == $user)
                {
                    $part = 0;
                    $x = 1;
                }
                else
                { $part = 0;
                    $x = 0;
                }
            }
            else
            {
                if ($idu == $user)
                {
                    $part = 1;
                    $x = 1;
                }
                else
                { $part = 1;
                    $x = 0;
                }
            }
            return $this->render('BaseBundle:Events:detailEvent.html.twig', array(
                'x'=>$x,
                'part'=>$part,
                'nom' => $nom,
                'Date' =>$date ,
                'image' => $image,
                'desc' => $description,
                'event' => $Event));
            /*$paginator=$this->get('knp_paginator');
            $Events=$paginator->paginate(
                $Events,
                $request->query->getInt('page',1),
                $request->query->getInt('page',4)
            );
            return $this->render('BaseBundle:Events:afficherEvent.html.twig',array(
                    'event'=>$Events,
                'dateP'=>$datej
            ));*/
            ?><script>alert('Merci d avoir participer à cet évènement);</script><?php
        }


        ?><script>alert('Vous avez deja participé à cet évènement ^_^');</script><?php
       /* $msg  =  'Vous avez déjà annuler votre participation!';


        $em=$this->getDoctrine()->getManager();
        $em->remove($participation);
        $Event->setNbreparticipation($Event->getNbreparticipation() - 1);
        $em->persist($Event);
        $em->flush();
        $etat=1;
        $events=$em->getRepository(Events::class)->TrierEventsClient($etat);*/
        $idu=$Event->getUser();
        $nom=$Event->getNom();
        $date=$Event->getDate();
        $image=$Event->getImage();
        $description=$Event->getDescription();
        if(empty($participation) )
        {
            if ($idu == $user)
            {
                $part = 0;
                $x = 1;
            }
            else
            { $part = 0;
                $x = 0;
            }
        }
        else
        {
            if ($idu == $user)
            {
                $part = 1;
                $x = 1;
            }
            else
            { $part = 1;
                $x = 0;
            }
        }
        return $this->render('BaseBundle:Events:detailEvent.html.twig', array(
            'x'=>$x,
            'part'=>$part,
            'nom' => $nom,
            'Date' =>$date ,
            'image' => $image,
            'desc' => $description,
            'event' => $Event));
      /*  $paginator=$this->get('knp_paginator');
        $Events=$paginator->paginate(
            $Events,
            $request->query->getInt('page',1),
            $request->query->getInt('page',4)
        );
        return $this->render('BaseBundle:Events:afficherEvent.html.twig',array('event'=>$Events));*/


    }

    public function pdfAction(Request $request){


        $snappy = $this->get('knp_snappy.pdf');
        $user=$this->getUser();
        $idevent=$request->get('idEvent');
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository("BaseBundle:Event")->find($idevent);
        $participants=$em->getRepository(Participation::class)->findParticipationDQL($idevent);

        $html = $this->renderView('@Base/Participation/pdf.html.twig',array(

            'participants'=>$participants,
            'event'=>$event
        ));

        $filename = 'myFirstSnappyPDF';
        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }
}