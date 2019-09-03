<?php
namespace Controllers\Home;


use \Core\ORM\EntityManager;
use \Entities\Sessions;
use \Core\Controllers\CallableInterface;
use Entities\Participant;

/**
 *
 * @author jean-luc
 *        
 */
final class HomeController implements CallableInterface
{

    /**
     */
    public function __construct()
    {}
    
    public function index() {
        echo "HomeController::index works !";
    }
    
    public function invoke(string $method, array $args) {
        call_user_func_array([$this,$method],$args);
    }
    
    public function session(int $id) {
            $em = \Core\ORM\EntityManager::getEntityManager()->getManager();
        
            $repository = $em = \Core\ORM\EntityManager::getEntityManager()->getRepository(Sessions::class);
            
            $session = $repository->find($id);
            
            echo "<h1>Session : " . $session->title() . "</h1>";
            
            // Loop over participants collection
            echo "<ul>";
            foreach ($session->getParticipants() as $participant) {
                echo "<li>" . $participant->getFirstName() . " " . $participant->getLastName() . "</li>";
            }
            echo "</ul>";
    }
    
    /**
     * Add a session into the database
     */
    public function addSession() {
        // Instanciate a new Sessions object, and sets some values
        $newSession = (new Sessions())
            ->title("Doctrine et PHP")
            ->availablePlaces(12);
        
        // Get the instance of my own EntityManager
        $em = \Core\ORM\EntityManager::getEntityManager()->getManager();
        
        // Call persistence
        $em->persist($newSession);
        
        // Commit the result
        $em->flush();
        
        echo "Session successfully created with the id : " . $newSession->getId();
    }
    
    /**
     * Update a Session and add some participants
     * @param int $id
     */
    public function updateSession(int $id) {
        // Get the instance of my own EntityManager
        $em = \Core\ORM\EntityManager::getEntityManager()->getManager();
        
        
        
        $session = $em->find(Sessions::class, $id);
        $session->endAt(new \DateTime("2019-09-09"));
        
        // Add some participants
        $participant = (new Participant())
            ->setLastName("Aubert")
            ->setFirstName("Jean-Luc")
            ->setSession($session); // Required to insure data integrity
        
        $session->addParticipant($participant);
        
        $session->addParticipant(
            (new Participant())
                ->setLastName("Manson")
                ->setFirstName("Albert")
                ->setSession($session)
        );
        
        $em->persist($session);
        
        $em->flush();
        
        echo "Session was updated and ends at " . $session->endAt()->format("d-m-Y");
        echo "<br>And contains : " . $session->getNbParticipants() . " participants";
    }
    
    public function deleteSession(int $id) {
        // Get the instance of my own EntityManager
        $em = \Core\ORM\EntityManager::getEntityManager()->getManager();
        
        
        
        $session = $em->find(Sessions::class, $id);
        
        $em->remove($session);
        $em->flush();
        
        echo "Session was deleted " . $id;
    }
    
    public function fixture() {
        $startDate = new \DateTime();
        $endDate = $startDate->add(new \DateInterval("P1D"));
        foreach(range(1, 10) as $indice ) {
            $session = (new Sessions())
                ->title("Session #" . $indice)
                ->beginAt($startDate)
                ->endAt($endDate)
                ->availablePlaces(10);
            
            \Core\ORM\EntityManager::getEntityManager()->getManager()->persist($session);
            
            $startDate->modify("+1 month");
            $endDate = $startDate->add(new \DateInterval("P1D"));
        }
        \Core\ORM\EntityManager::getEntityManager()->getManager()->flush();
    }
}

