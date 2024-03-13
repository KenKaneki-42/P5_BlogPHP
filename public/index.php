<?php

use Core\Router;
use Core\Exception\RedirectException;
use Dotenv\Dotenv;

//Autoload
require "../vendor/autoload.php";

define("CONFIG_DIR", realpath(dirname(__DIR__)) . "/config");
define("TEMPLATES_DIR", realpath(dirname(__DIR__)) . "/templates");
define("ROOT_DIR", dirname(__DIR__));

// .env
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

//Starting session
if(session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Start the routing
try {
  Router::start();
} catch (\Exception $e) {
  echo $e->getMessage();
}
