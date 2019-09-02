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
    
    /**
     * Sets routes of the application
     * @todo Better with annotation reader to read routes from Controllers
     */
    private function setRoutes() {
        $this->router->map(
                "GET",
                "/",
                "\Controllers\Home\HomeController#index"
            );
    }
    
    private function resolver() {
        $routerMatches = $this->router->match();
        
        if( is_array($routerMatches) ) {
            $controllerComponents = explode("#", $routerMatches["target"]);
            // Make an instance of the controller
            $controllerName = $controllerComponents[0];
            $controller = new $controllerName();
            // Call the required method
            $controller->{$controllerComponents[1]}();
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