<?php

namespace App\Controller;

use Core\component\AbstractController;

class ErrorController extends AbstractController
{

  public function notFound()
  {
    $errors = [
      '404 not found'
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
