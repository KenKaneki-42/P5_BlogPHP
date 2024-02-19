<?php

namespace App\Service\Handler;

class PostHandler {
  public function validatePostData(string $title, string $content, string $tagline, int $userId): array
  {
    $errors = [];

    if (empty($title)) {
      $errors['title'] = 'Le titre ne peut pas être vide.';
    }
    if (strlen($title) < 3) {
      $errors['title'] = 'Le titre doit faire plus de 3 caractères.';
    }
    if (empty($content)) {
      $errors['content'] = 'Le contenu ne peut pas être vide.';
    }
    if (strlen($content) < 53) {
      // 23 caractères de bases avec trix
      $errors['content'] = "Le contenu de l'article doit faire plus de 10 caractères.";
    }

    return $errors;
  }
}
