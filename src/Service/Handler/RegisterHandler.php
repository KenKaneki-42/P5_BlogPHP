<?php

namespace App\Service\Handler;

class RegisterHandler {
  public function validateUserData(string $lastName, string $firstName, string $password, string $confirmedPassword, string $email)
  {
    $errors = [];

    if (empty($lastName)) {
      $errors['lastName'] = 'Le nom ne peut pas être vide.';
    }
    if (empty($firstName)) {
      $errors['firstName'] = 'Le prénom ne peut pas être vide.';
    }
    if ($password !== $confirmedPassword) {
      $errors['password'] = 'Les mots de passes ne correspondent pas';
    }
    if ($email )
    $regex = "/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,}$/gm";
    return $errors;
  }
}
