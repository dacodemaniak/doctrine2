<?php
require_once join(DIRECTORY_SEPARATOR, [__DIR__, "/src/Core/ORM/EntityManager.php"]);

// Defines space name from where pick the ConsoleRunner class
use Doctrine\ORM\Tools\Console\ConsoleRunner;

// Get the instance of my own EntityManager
$instance = \Core\ORM\EntityManager::getEntityManager();

// Finally returns the Helper that allow Doctrine CLI commands
return ConsoleRunner::createHelperSet($instance->getManager());
