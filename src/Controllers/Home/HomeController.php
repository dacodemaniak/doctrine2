<?php
namespace Controllers\Home;


use \Core\Core;
use \Core\ORM\EntityManager;
use \Entities\Sessions;
use \Core\Controllers\CallableInterface;
use Entities\Participant;
use Entities\Participation;
use Core\Controllers\Controller;
use Entities\TodoList;

/**
 *
 * @author jean-luc
 *        
 */
final class HomeController extends Controller implements CallableInterface
{

    /**
     */
    public function __construct()
    {}
    
    public function index() {
        $this->templateName = __DIR__ . "/../../../Templates/Home/index.tpl";
    }
    
    public function invoke(string $method, array $args) {
        call_user_func_array([$this,$method],$args);
    }
    
    public function addTodoList() {
        $em = \Core\ORM\EntityManager::getEntityManager()->getManager();
        
        // Create an instance of TodoList
        $todoList = new TodoList();
        $todoList->setTitle("Ma nouvelle todoList");
        
        $em->persist($todoList);
        $em->flush();
        
        echo "Got it! Votre nouvelle todolist porte le numéro : " . $todoList->getId();
    }
    
    public function session(int $id) {
            $em = \Core\ORM\EntityManager::getEntityManager()->getManager();
        
            $repository = $em = \Core\ORM\EntityManager::getEntityManager()->getRepository(Sessions::class);
            
            $session = $repository->findById($id);
            
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
    
    public function participation() {
        $sessionRepo = \Core\ORM\EntityManager::getEntityManager()->getRepository(Sessions::class);
        $participantRepo = \Core\ORM\EntityManager::getEntityManager()->getRepository(Participant::class);
        
        $em = \Core\ORM\EntityManager::getEntityManager()->getManager();
        
        // 1. Inscrire tous les participants à la session 12
        $session = $sessionRepo->find(12);
        $participants = $participantRepo->findAll();
        
        foreach ($participants as $participant) {
            $participation = (new Participation())
                ->setParticipant($participant)
                ->setSession($session)
                ->signinDate(new \DateTime());
            $em->persist($participation); // Needs to persist into database
        }
        
        // 2. Inscrire le participant 2 à la session 13
        $participation = (new Participation())
            ->setParticipant($participantRepo->find(2))
            ->setSession($sessionRepo->find(13))
            ->signinDate(new \DateTime("2019-09-04"));
        $em->persist($participation);
        
        // All objects are ready to write
        $em->flush();
    }
    
    /**
     * Get sessions signed by a Participant
     * 
     * @param int $id Id of a Participant
     */
    public function listSessions(int $id) {
        $repo = \Core\ORM\EntityManager::getEntityManager()->getRepository(Participant::class);
        
        $participant = $repo->find($id);
        
        // Now, let's output results
        echo "<h1>" . $participant->getFirstName() . " " . $participant->getLastName() . "</h1>";
        
        // Loop over participations to get the session
        echo "<ul>";
        foreach ($participant->getParticipations() as $participation) {
            echo    "<li>" . $participation->getSession()->title() . 
                    " [" . $participation->signinDate()->format("d-m-Y") . "]</li>";
        }
        echo "</ul>";
    }
    
    public function sessions() {
        $repo = \Core\ORM\EntityManager::getEntityManager()->getRepository(Sessions::class);
        
        $participationsRepo = \Core\ORM\EntityManager::getEntityManager()->getRepository(Participation::class);
        
        // Get all sessions
        $sessions = $repo->findAll();
        
        echo "<ul>";
        foreach ($sessions as $session) {
            // Gets participations for this session
            $participations = $participationsRepo->findBy([
                "session" => $session
            ]);
            
            echo "<li><strong>" . $session->title() . "</strong> [ " . count($participations) . " participants ]";
            if (count($participations)) {
                echo "<ul>";
                foreach ($participations as $participation) {
                    echo "<li>" . $participation->getParticipant()->fullName . "</li>";
                }
                echo "</ul>";
            }
            echo "</li>";
        }
        echo "</ul>";
        
        
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

