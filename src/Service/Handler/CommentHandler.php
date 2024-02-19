<?php

namespace App\Service\Handler;

class CommentHandler {
  public function validateCommentData(string $content, int $postId, int $userId): array
  {
    $errors = [];

    if (strlen($content) < 3) {
      $errors['content'] = "Le contenu du commentaire doit faire plus de 3 caractères.";
    }

    return $errors;
  }
}
