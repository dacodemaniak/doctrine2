<?php
/**
 * @name EntityManager
 * @package Core\ORM
 * @author IDea Factory - Sept. 2019
 * @version 1.0.0
 */
namespace Core\ORM;

use \Doctrine\ORM\Tools\Setup as Setup;
use \Doctrine\ORM\EntityManager as DoctrineManager;

class EntityManager {
	/**
	 * Static instance of that class
	 */
	private static $instance;

	/**
	 * @var array
	 * Stores the pathes of entities, default src/Entities
	 */
	private $entityPathes;
	
	/**
	 * @var boolean
	 * Determines wether Manager runs in dev mode or not
	 */
	private $isDevMode = true;
	
	/**
	 * Defines proxy directory for entities, default null
	 * @var string
	 */
	private $proxyDir = null;
	
	/**
	 * Defines cache directory
	 * @var string
	 */
	private $cache = null;
	
	/**
	 * Defines the annotation reader to use
	 * @var boolean
	 */
	private $useSimpleAnnotationReader = false;
	
	/**
	 * Stores Database configuration and driver
	 * @var array
	 */
	private $dbConfiguration = [
	    "driver" => "pdo_mysql",
	    "host" => "127.0.0.1",
	    "charset" => "utf8",
	    "user" => "doctrine_dba",
	    "password" => "doctrine",
	    "dbName" => "doctrine"
	];
	
	/**
	 * Doctrine Entity Manager
	 * @var Doctrine\ORM\EntityManager
	 */
	private $manager = null;
	
	/**
	 * Private constructor of EntityManager Singleton
	 */
	private function __construct() {
	    $this->entityPathes = [
	        join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "Entities"])
	    ];
	    
	    $this->manager = $this->process();
	}
	
	/**
	 * Main method for Singleton
	 */
	public static function getEntityManager(): \Core\ORM\EntityManager {
	    if (self::$instance === null) {
	        self::$instance = new EntityManager();
	    }
	    
	    return self::$instance;
	}
	
	/**
	 * Returns Doctrine Entity Manager
	 * @return Doctrine\ORM\EntityManager
	 */
	public function getManager(): \Doctrine\ORM\EntityManager {
	    return $this->manager;
	}
	
	/**
	 * Process the Doctrine Entity Manager Configuration and returns it
	 * @return \Doctrine\ORM\EntityManager
	 */
	private function process() {
	    $config = Setup::createAnnotationMetadataConfiguration(
	           $this->entityPathes,
	           $this->isDevMode,
	           $this->proxyDir,
	           $this->cache,
	           $this->useSimpleAnnotationReader
	        );
	    return DoctrineManager::create($this->dbConfiguration, $config);
	}
}
