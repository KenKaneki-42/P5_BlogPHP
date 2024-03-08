<?php

namespace App\Controller;

use Core\Component\AbstractController;

class ErrorController extends AbstractController
{
  // Generic private method for handling error response
  private function handleError(string $errorMessage, int $errorCode): string
  {
      http_response_code($errorCode);
      return $this->render("front/errors", ['errors' => [$errorMessage]]);
  }

  public function notFound(): string
  {
      return $this->handleError('404 Not Found', 404);
  }

  public function forbidden(): string
  {
      return $this->handleError('403 Forbidden', 403);
  }

  public function error(): string
  {
      return $this->handleError('500 Internal Server Error', 500);
  }
}
