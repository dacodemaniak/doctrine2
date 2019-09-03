<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use \Entities\Sessions;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * 
 * @author jean-luc
 *
 * @ORM\Entity
 * @ORM\Table(name="participants")
 */
class Participant
{

    /**
     * Primary key of one Participant
     * @var int
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * Lastname of one Participant
     * @var string
     * 
     * @ORM\Column(type="string", name="last_name")
     */
    protected $lastName;
    
    /**
     * Firstname of one Participant
     * @var string
     * 
     * @ORM\Column(type="string", name="first_name")
     */
    protected $firstName;
    
    /**
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity=Participation::class, cascade={"persist"}, mappedBy="participant")
     */
    protected $participations;
    
    
    public function __construct()
    {
        $this->participations = new ArrayCollection();
    }
    
    /**
     * @return number
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string Lastname of this participant
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }



    /**
     * @param string $lastName
     * @return Participant
     */
    public function setLastName($lastName): Participant
    {
        $this->lastName = $lastName;
        
        return $this;
    }

    /**
     * @param string $firstName
     * @return Participant
     */
    public function setFirstName($firstName): Participant
    {
        $this->firstName = $firstName;
        return $this;
    }
    
    /**
     * Add this participant to the participations
     * @param Participation 
     * @return Participant
     */
    public function addParticipation(Participation $participation): Participant {
        $this->participations->add($participation);
        
        return $this;
    }

}

