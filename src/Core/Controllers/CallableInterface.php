<?php
namespace Core\Controllers;

/**
 *
 * @author jean-luc
 *        
 */
interface CallableInterface
{
    public function invoke(string $method, array $args);
}

