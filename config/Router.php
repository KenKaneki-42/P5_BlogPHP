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

    // Global handler errors
    self::error(function (\Pecee\Http\Request $request, \Exception $exception) {
      if ($exception instanceof \Pecee\SimpleRouter\Exceptions\NotFoundHttpException) {
          response()->redirect('/not-found');
      } elseif ($exception instanceof \Core\Exception\ForbiddenAccessException) {
          response()->redirect('/forbidden');
      } else {
          response()->redirect('/error');
      }
  });


    // Do initial stuff
    parent::start();
  }
}
