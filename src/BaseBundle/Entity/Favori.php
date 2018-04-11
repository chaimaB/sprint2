<?php
/**
 * Created by PhpStorm.
 * User: CHAIMA
 * Date: 10/04/2018
 * Time: 15:27
 */

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Favori
 *
 * @ORM\Table(name="favori")
 * @ORM\Entity(repositoryClass="BaseBundle\Repository\FavoriRepository")
 */
class Favori
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_fav", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFav;
    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\Event")
     * @ORM\JoinColumn(name="event",referencedColumnName="id_event", onDelete="cascade")
     */
    private $event;
    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\User")
     * @ORM\JoinColumn(name="user",referencedColumnName="id", onDelete="cascade")
     */
    private $user;

    /**
     * @return int
     */
    public function getIdFav()
    {
        return $this->idFav;
    }

    /**
     * @param int $idFav
     */
    public function setIdFav($idFav)
    {
        $this->idFav = $idFav;
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


}