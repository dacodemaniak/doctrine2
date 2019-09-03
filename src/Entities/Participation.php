<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * 
 * @author jean-luc
 *
 * @ORM\Entity
 * @ORM\Table(
 *  name="participants_to_sessions",
 *  uniqueConstraints = {
 *      @ORM\UniqueConstraint(name="participant_session_unique", columns={"participant_id", "session_id"})}
 * )
 */
final class Participation
{
    
    /**
     * 
     * @var integer
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * 
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime")
     */
    protected $signinDate;
    
    /**
     * 
     * @var \Entities\Participant
     * 
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="participations")
     */
    protected $participant;
    
    /**
     * 
     * @var \Entities\Sessions
     * 
     * @ORM\ManyToOne(targetEntity=Sessions::class)
     */
    protected $session;
    
    
    public function __construct()
    {}
    
    public function signinDate(\DateTime $date = null): self {
        if ($date === null) {
            return $this->signinDate;
        }
        
        $this->signinDate = $date;
        
        return $this;
    }
    
    public function setParticipant(Participant $participant): self {
        $this->participant = $participant;
        return $this;
    }
    
    public function getParticipant(): Participant {
        return $this->participant;
    }
    
    public function setSession(Sessions $session): self {
        $this->session = $session;
        
        return $this;
    }
    
    public function getSession(): Sessions {
        return $this->session;
    }
}

