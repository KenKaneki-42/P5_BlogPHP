<?php

namespace App\Service\Handler;

class CommentHandler {


  private function validateCommentData(string $title, string $content, string $tagline, int $userId): array
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
    // if (empty($tagline)) {
    //   $errors['tagline'] = 'La tagline ne peut pas être vide.';
    // }
    // if (empty($userId)) {
    //   $errors['userId'] = "L'identifiant de l'utilisateur ne peut pas être vide.";
    // }

    return $errors;
  }
}
