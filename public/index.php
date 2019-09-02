<?php
/**
 * @name index Dispatcher de l'application PHP
 * @author IDea Factory - Sept. 2019
 * @package none
 * @version 1.0.0
 */

ini_set("display_errors", true);
error_reporting(E_ALL^E_NOTICE);

require_once("./../src/Core/Core.php");

use Core\Core;

$appCore = Core::get();