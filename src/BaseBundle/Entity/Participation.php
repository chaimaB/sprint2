<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table(name="participation")
 * @ORM\Entity(repositoryClass="BaseBundle\Repository\ParticipationRepository")
 */
class Participation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_participation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idParticipation;

    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\Event")
     * @ORM\JoinColumn(name="id_event",referencedColumnName="id_event", onDelete="cascade")
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\User")
     * @ORM\JoinColumn(name="id_user",referencedColumnName="id", onDelete="cascade")
     */
    private $user;


    /**
     * @return int
     */
    public function getIdParticipation()
    {
        return $this->idParticipation;
    }

    /**
     * @param int $idParticipation
     */
    public function setIdParticipation($idParticipation)
    {
        $this->idParticipation = $idParticipation;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


}

