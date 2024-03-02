<?php

namespace App\Controller;

use Core\component\AbstractController;

class ErrorController extends AbstractController
{

  public function notFound()
  {
    $errors = [
      '404 Not found'
    ];

    return $this->render("front/errors", ['errors' => $errors]);
  }
  public function forbidden()
  {
    $errors = [
      '403 Forbidden'
    ];

    return $this->render("front/errors", ['errors' => $errors]);
  }
}
