<?php
/**
 * Created by PhpStorm.
 * User: CHAIMA
 * Date: 03/04/2018
 * Time: 16:25
 */

namespace BaseBundle\Controller;


use BaseBundle\Entity\Event;
use BaseBundle\Entity\Favori;
use BaseBundle\Entity\Participation;
use BaseBundle\Form\EventType;
use BaseBundle\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EventController extends Controller
{
    public function ajoutEventAction(Request $request){
        $event=new Event();
        $form=$this->createForm(EventType::class,$event);
        $form->handleRequest($request);
        if($form->isValid()){
            $dir="C:\\wamp64\\www\\medina\\web\\EventsImages";
            $file=$form['image']->getData();
            $event->setImage($event->getNom().".png,.jpg,.gif");
            $file->move($dir,$event->getImage());
            $event->setUser($this->getUser());
            $event->setEtat(1);
            $em=$this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('afficherEvent');
        }
        return $this->render('BaseBundle:Events:ajout.html.twig', array(
            "form"=>$form->createView()

        ));

    }

    public function archieveAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $etat=1;
        $event=$em->getRepository(Event::class)->TrierEventsDoneClient($etat);
        $paginator=$this->get('knp_paginator');
        $event=$paginator->paginate(
            $event,
            $request->query->getInt('page',1),
            $request->query->getInt('page',4)
        );
        return $this->render('BaseBundle:Events:afficherEvent.html.twig',array('event'=>$event));
    }

    public function deleteEventAction(Request $request ){
        $idEvent=$request->get('idEvent');
        $user=$this->getUser();
        //$idu=$user->getId();
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository("BaseBundle:Event")->find($idEvent);
        if ($event->getUser() == $user) {

            $em->remove($event);
            $em->flush();

            return ($this->redirectToRoute("afficherEvent"));
        }
        $msg='Vous ne pouvez pas supprimer un évènement qui n est pas le votre';
       // $etat=1;
        //$events=$em->getRepository(Events::class)->TrierEventsClient($etat);
        ?><script>alert('Vous ne pouvez pas supprimer un évènement qui n est pas le votre ');</script><?php
       // return ($this->redirectToRoute("detailEvent"));
        return $this->render('BaseBundle:Events:detailEvent.html.twig',array(
                'event'=>$event));
    }

    public function detailEventAction(Request $request)
    {
        $iduser=$this->getUser();
        $id=$request->get("idEvent");
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository("BaseBundle:Event")->find($id);
        $participation=$em->getRepository('BaseBundle:Participation')->findOneBy(array('user'=>$iduser,'event'=>$id));
        $idu=$event->getUser();
        $nom=$event->getNom();
        $date=$event->getDate();
        $image=$event->getImage();
        $description=$event->getDescription();
        if(empty($participation) )
        {
            if ($idu == $iduser)
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
            if ($idu == $iduser)
            {
                $part = 1;
                $x = 1;
            }
            else
            { $part = 1;
                $x = 0;
            }
        }
        /*$manager = $this->get('mgilet.notification');
        $notif = $manager->createNotification('teeeest');
        $notif->setMessage('This a notification.');
        $notif->setLink('http://symfony.com/');
        // or the one-line method :
        // $manager->createNotification('Notification subject','Some random text','http://google.fr');

        // you can add a notification to a list of entities
        // the third parameter ``$flush`` allows you to directly flush the entities
        $manager->addNotification(array($this->getUser()), $notif, true);*/
        return $this->render('BaseBundle:Events:detailEvent.html.twig', array(
                'x'=>$x,
                'part'=>$part,
                'nom' => $nom,
            'Date' =>$date ,
            'image' => $image,
            'desc' => $description,
            'event' => $event));

    }



    public function updateEventAction(Request $request , $id){
        $em=$this->getDoctrine()->getManager();
        $user=$this->getUser();
       // $idUser=$user->getId();
        $event=$em->getRepository("BaseBundle:Event")->find($id);
        $form=$this->createForm(EventType::class,$event);
        $form->handleRequest($request);
        if($event->getUser() == $user) {


            if ($form->isValid()) {
                $dir = "C:\\wamp64\\www\\medina\\web\\EventsImages";
                $file = $form['image']->getData();
                $event->setImage($event->getNom() . ".png,.jpg,.gif");
                $file->move($dir, $event->getImage());
                $em->persist($event);
                $em->flush();
                return $this->redirect($this->generateUrl("afficherEvent"));

                  $em->persist($event);
                  $em->flush();
                //return $this->redirectToRoute('afficherEvent');

            }

            return $this->render('BaseBundle:Events:updateEvent.html.twig', array(
                "form" => $form->createView()));
        }

        ?><script>alert('Vous ne pouvez pas modifier un évènement qui n est pas le votre ');</script><?php
        return $this->render('BaseBundle:Events:detailEvent.html.twig',array('event'=>$event));

    }

    public function afficherEventAction(Request $request){
        $event=new Event();
        $etat = 1;
        $em=$this->getDoctrine()->getManager();
        $event = $this->getDoctrine()->getManager();
        $events= $event->getRepository("BaseBundle:Event")->TrieEvents($etat);
        $favori=$em->getRepository(Favori::class)->findAll();

        $paginator=$this->get('knp_paginator');
        $events=$paginator->paginate(
                $events,
                $request->query->getInt('page',1),
                $request->query->getInt('page',4)
        );

        return $this->render('BaseBundle:Events:afficherEvent.html.twig',array(

            'event'=>$events,
            'favori'=>$favori
        ));

    }

    public function rechercheEventAjaxAction(Request $request){
        $user=$this->getUser();
        if ($request->isXmlHttpRequest()) {
            $nom = $request->get('nom');
            $em = $this->getDoctrine()->getManager();
            $E = $em->getRepository(Event::class)->findEventDQL($nom);
            $ser = new Serializer(array(new ObjectNormalizer()));
            $data = $ser->normalize($E);
            return new JsonResponse($data);
        }
        return $this->render('BaseBundle:Events:recherche.html.twig', array(
        ));
    }


    public function MyEventsAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $user=$this->getUser();
        $event=$em->getRepository(Event::class)->findBy(array("user" => $user));
        $paginator=$this->get('knp_paginator');
        $event=$paginator->paginate(
            $event,
            $request->query->getInt('page',1),
            $request->query->getInt('page',4)
        );
        return $this->render('BaseBundle:Events:afficherEvent.html.twig',array('event'=>$event));
    }



    public function ListeParticipantsAction(Request $request){
        $user=$this->getUser();
        $idevent=$request->get('idEvent');
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository("BaseBundle:Event")->find($idevent);
        $participants=$em->getRepository(Participation::class)->findParticipationDQL($idevent);
        $datej=new \DateTime('now');
        $week=date("Y-m-d", strtotime("-1 week"));

        return $this->render('BaseBundle:Events:listeParticipants.html.twig',array(

                'participants'=>$participants,
            'event'=>$event,
            'dateP'=>$datej
        ));
    }

    public function pdfAction(Request $request){
        $snappy = $this->get('knp_snappy.pdf');

        $user=$this->getUser();
        $id=$request->get("idEvent");
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository("BaseBundle:Event")->find($id);

        $html = $this->renderView('@Base/Events/pdf.html.twig',array(
            'event'=>$event,
            'user'=>$user
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