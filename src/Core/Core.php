<?php
/**
 * @name Core Chargement de l'application PHP
 * @author IDea Factory - Sept. 2019
 * @package Core
 * @version 1.0.0
 */
namespace Core;

use \TemplateEngine\Smarty\Smarty;
use \Controllers\Home\HomeController;
use Core\TemplateEngine\TemplateEngineInterface;

require_once(__DIR__ . "/../../vendor/autoload.php");

final class Core {
    
    /**
     * Instance du coeur de l'application
     * @var Core
     */
    private static $coreInstance;
    
    /**
     * Instance du moteur de template (Smarty)
     * @var \TemplateEngine\Smarty\Smarty
     */
    private $templateEngine;
    
    /**
     * Router used to route all requests
     * @var \AltoRouter
     */
    private $router;
    
    private function __construct() {
        spl_autoload_register(array(__CLASS__,"autoload"));
        
        $this->templateEngine = new TemplateEngine\Smarty\Smarty();
        
        /**
         * Router instanciation
         */
        $this->router = new \AltoRouter();
        
        /**
         * Sets the routes of the app
         */
        $this->setRoutes();
        
        $this->resolver();
        
    }

    
    public static function get(): Core {
        if (self::$coreInstance !== null) {
            // Il existe déjà une instance... on la retourne
            return self::$coreInstance;
        }
        
        // Intanciation de la classe Core
        self::$coreInstance = new Core();
        
        return self::$coreInstance;
    }
    
    public function getTemplateEngine(): TemplateEngineInterface {
        return $this->templateEngine;
    }
    
    /**
     * Sets routes of the application
     * @todo Better with annotation reader to read routes from Controllers
     */
    private function setRoutes() {
        $this->router->map(
            "GET",
            "/todolist/add",
            "\Controllers\Home\HomeController#addTodoList"
        );
        
        $this->router->map(
                "GET",
                "/",
                "\Controllers\Home\HomeController#index"
            );

        $this->router->map(
            "GET",
            "/sessions",
            "\Controllers\Home\HomeController#sessions"
        );
        
        $this->router->map(
            "GET",
            "/session/[i:id]",
            "\Controllers\Home\HomeController#session"
        );
        
        $this->router->map(
                "GET",
                "/session/participant/add",
                "\Controllers\Home\HomeController#participation"
            );
        $this->router->map(
            "GET",
            "/session/participant/[i:id]",
            "\Controllers\Home\HomeController#listSessions"
        );
        
        $this->router->map(
                "GET",
                "/session/add",
                "\Controllers\Home\HomeController#addSession"
            );
        
        $this->router->map(
                "GET",
                "/session/update/[i:id]",
                "\Controllers\Home\HomeController#updateSession"
            );
        
        $this->router->map(
                "GET",
                "/session/delete/[i:id]",
                "\Controllers\Home\HomeController#deleteSession"
            );
        $this->router->map(
                "GET",
                "/session/hydrate",
                "\Controllers\Home\HomeController#fixture"
            );
    }
    
    private function resolver() {
        $routerMatches = $this->router->match();
        
        if( is_array($routerMatches) ) {
            $controllerComponents = explode("#", $routerMatches["target"]);
            // Make an instance of the controller
            $controllerName = $controllerComponents[0];
            $controller = new $controllerName();
            
            // Check for params
            if ($routerMatches["params"]) {
                //$controller->{$controllerComponents[1]}($routerMatches["params"]["id"]);
                $controller->invoke($controllerComponents[1], array_values($routerMatches["params"]));
            } else {
                // Call the required method
                $controller->{$controllerComponents[1]}();
            }
        }
    }
    
    /**
     * Chargement automatique des classes
     */
    private static function autoload(string $className) {
        $classParts = explode("\\", $className);
        
        $class = array_pop($classParts);
        
        $classPath = __DIR__ . "/../" . implode("/", $classParts) . "/" . $class . ".php";
        
        if (file_exists($classPath)) {
            require_once($classPath);
        } else {
            if (property_exists(\Core\Core::class, "vendorPathes")) {
                // On cherche dans le dossier vendor
                foreach(self::$vendorPathes as $classRoot => $path) {
                    $classPath = __DIR__ . "/../../vendor/" . $path . "/" . $class . ".php";
                    if (file_exists($classPath)) {
                        require_once($classPath);
                        break;
                    }
                }
            }
        }
    }
}