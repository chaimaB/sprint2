<?php
/**
 * Created by PhpStorm.
 * User: CHAIMA
 * Date: 04/04/2018
 * Time: 02:30
 */

namespace BaseBundle\Repository;


class EventRepository extends \Doctrine\ORM\EntityRepository
{
    public function findEventDQL($nom)
    {
        $Query=$this->getEntityManager()
            ->createQuery(" 
               select v from BaseBundle:Event v where v.nom LIKE :nom")
            ->setParameter('nom','%'.$nom.'%');
        return $Query->getResult();

    }

    public function TrierEventsDoneClient($etat)
    {
        $query = $this->getEntityManager()
            ->createQuery("
        select s  from BaseBundle:Event s where s.etat like :etat AND s.date < CURRENT_DATE () ORDER BY s.date")->setParameter('etat','%'.$etat.'%');

        return $query->getResult();

    }

    public function TrieEvents($etat)
    {
        $query = $this->getEntityManager()
            ->createQuery("
        select s  from BaseBundle:Event s where s.etat like :etat AND s.date > CURRENT_DATE () ORDER BY s.date")->setParameter('etat','%'.$etat.'%');

        return $query->getResult();

    }
    public function TrierEventsClient($etat)
    {
        $query = $this->getEntityManager()
            ->createQuery("
        select s  from BaseBundle:Event s where s.etat like :etat AND s.date > CURRENT_DATE () ORDER BY s.date")->setParameter('etat','%'.$etat.'%');

        return $query->getResult();

    }

}