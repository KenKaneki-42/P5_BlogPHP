<?php

/**
 * Custom router which handles default middlewares, default exceptions and things
 * that should be happen before and after the router is initialised.
 */

namespace Core;

//Call Router Library
use Pecee\SimpleRouter\SimpleRouter;

class Router extends SimpleRouter
{
  /**
   * @throws \Exception
   * @throws \Pecee\Http\Middleware\Exceptions\TokenMismatchException
   * @throws \Pecee\SimpleRouter\Exceptions\HttpException
   * @throws \Pecee\SimpleRouter\Exceptions\NotFoundHttpException
   */
  public static function start(): void
  {

    require_once '../config/helpers.php';

    /* Load external routes file */
    require_once '../config/routes.php';

    // Do initial stuff
    parent::start();
  }
}
