<?php

// site front-controller

// display errors, warning and notices settings
ini_set('display_errors',1);
error_reporting(E_ALL);

session_start();

// load all system files
define('ROOT', dirname(dirname(__FILE__)));

require_once(ROOT.'/app/components/Autoload.php');

// create and call Router
$router = new Router();
$router->run();
