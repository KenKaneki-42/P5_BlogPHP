<?php

use Core\Router;
use Core\Exception\RedirectException;
use Dotenv\Dotenv;
// use Core\component;
// use Core\Security\CookieAuth;

//Autoload
require "../vendor/autoload.php";

define("CONFIG_DIR", realpath(dirname(__DIR__)) . "/config");
define("TEMPLATES_DIR", realpath(dirname(__DIR__)) . "/templates");
define("ROOT_DIR", dirname(__DIR__));

// .env
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

//Starting session
session_start();

//Autolog if user remember
// $cookieAuth = new CookieAuth();
// $cookieAuth->AuthWithCookie();

// Start the routing
try {
  Router::start();
} catch (RedirectException $e) {
  header('Location: ' . $e->getUrl());
  exit();
}

if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['flash_message'])) {
  unset($_SESSION['flash_message']);
}
