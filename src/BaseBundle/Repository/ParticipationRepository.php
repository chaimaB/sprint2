<?php
/**
 * Created by PhpStorm.
 * User: CHAIMA
 * Date: 03/04/2018
 * Time: 23:06
 */

namespace BaseBundle\Repository;



class ParticipationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findParticipationDQL($idevent)
    {
        $Query=$this->getEntityManager()
            ->createQuery("
        select p  from BaseBundle:Participation p where p.event =:idevent ")
//            ->createQuery(" select v from ClientBundle:Participation v where v.idevent like :idevent  ")
            ->setParameter('idevent',$idevent);


        return $Query->getResult();

    }

}