<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use \Entities\Participant;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @author IDea Factory - Sept. 2019
 * 
 * @ORM\Entity
 * @ORM\Table(name="sessions")
 */
final class Sessions
{

    /**
     * Id of this session
     * @var int
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * Title of the session
     * @var string
     * 
     * @ORM\Column(type="string")
     */
    protected $title;
    
    /**
     * Beginning of this session
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime", name="begin_at")
     */
    protected $beginAt;
    
    /**
     * End of this session
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime", name="end_at", nullable=true)
     */
    protected $endAt;
    
    /**
     * Available places for this session
     * @var int
     * 
     * @ORM\Column(type="integer", name="available_places")
     */
    protected $availablePlaces;
    
    /**
     * Array of participants of one Session
     * @var array
     * 
     * @ORM\OneToMany(targetEntity=\Entities\Participant::class, cascade={"persist"}, mappedBy="session")
     */
    protected $participants;
    
    public function __construct(){
        $this->beginAt = new \DateTime();
        $this->availablePlaces = 10;
        
        $this->participants = new ArrayCollection();
    }
    
    /**
     * Returns the auto generated id of this Session
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }
    
    /**
     * Sets or returns title attribute
     * @param string $title
     * @return string|\Entities\Sessions
     */
    public function title(string $title = null) {
        if ($title === null) {
            return $this->title;
        }
        
        $this->title = $title;
        return $this;
    }
    
    /**
     * Sets or returns the beginAt attribute
     * @param \DateTime $date
     * @return \DateTime|\Entities\Sessions
     */
    public function beginAt(\DateTime $date = null) {
        if ($date === null) {
            return $this->beginAt;
        }
        
        $this->beginAt = $date;
        return $this;
    }
    
    /**
     * Sets or returns the endAt attribute
     * @param \DateTime $date
     * @return \DateTime|\Entities\Sessions
     */
    public function endAt(\DateTime $date = null) {
        if ($date === null) {
            return $this->endAt;
        }
        
        $this->endAt = $date;
        
        return $this;
    }
    
    /**
     * Sets or returns the availablePlaces attribute
     * @param int $places
     * @return number|\Entities\Sessions
     */
    public function availablePlaces(int $places = null) {
        if ($places === null) {
            return $this->availablePlaces;
        }
        
        $this->availablePlaces = $places < 1 ? 1 : $places;
        
        return $this;
    }
    
    /**
     * Adds a participant to this Session
     * 
     * @param Participant $participant
     * @return Sessions
     */
    public function addParticipant(Participant $participant): Sessions {
        $this->participants->add($participant);
        
        return $this;
    }
    
    /**
     * Removes a Participant from the collection of this Sessions's Participants
     * @param Participant $participant
     * @return Sessions
     */
    public function removeParticipant(Participant $participant): Sessions {
        $this->participants->remove($participant);
        
        return $this;
    }
    
    public function getParticipants(): \Doctrine\ORM\PersistentCollection {
        return $this->participants;
    }
    
    public function getNbParticipants(): int {
        return $this->participants->count();
    }
}
