<?php

use Core\Router;
// use Core\component;
// use Core\Security\CookieAuth;

//Autoload
require "../vendor/autoload.php";

// define("CONFIG_DIR", realpath(dirname(__DIR__)) . "../config" );
define("CONFIG_DIR", realpath(dirname(__DIR__)) . "/config" );
// define("TEMPLATES_DIR", realpath(dirname(__DIR__)) . "../templates" );
define("TEMPLATES_DIR", realpath(dirname(__DIR__)) . "/templates" );
// var_dump(CONFIG_DIR);
// var_dump(TEMPLATES_DIR);

define("ROOT_DIR", dirname(__DIR__));

//Starting session
session_start();

//Autolog if user remember
// $cookieAuth = new CookieAuth();
// $cookieAuth->AuthWithCookie();

// Start the routing
Router::start();
