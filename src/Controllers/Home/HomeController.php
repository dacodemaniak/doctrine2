<?php
namespace Controllers\Home;


use \Core\ORM\EntityManager;
use \Entities\Sessions;
use \Core\Controllers\CallableInterface;

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
    
    public function updateSession(int $id) {
        // Get the instance of my own EntityManager
        $em = \Core\ORM\EntityManager::getEntityManager()->getManager();
        
        
        
        $session = $em->find(Sessions::class, $id);
        $session->endAt(new \DateTime("2019-09-09"));
        
        $em->persist($session);
        $em->flush();
        
        echo "Session was updated and ends at " . $session->endAt()->format("d-m-Y");
        
    }
}

