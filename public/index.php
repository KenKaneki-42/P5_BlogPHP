<?php

use Core\Router;
// use Core\component;
// use Core\Security\CookieAuth;

//Autoload
require "../vendor/autoload.php";

define("CONFIG_DIR", realpath(dirname(__DIR__)) . "/config");
define("TEMPLATES_DIR", realpath(dirname(__DIR__)) . "/templates");
define("ROOT_DIR", dirname(__DIR__));

//Starting session
session_start();

//Autolog if user remember
// $cookieAuth = new CookieAuth();
// $cookieAuth->AuthWithCookie();

// Start the routing
Router::start();

if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['flash_message'])) {
  unset($_SESSION['flash_message']);
}
