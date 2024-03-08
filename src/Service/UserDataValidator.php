<?php

namespace App\Service;

use App\Repository\UserRepository;

class UserDataValidator
{
  public static function validateUserData(string $lastName, string $firstName, string $password, string $confirmedPassword, string $email): array
  {
    $errors = [];

    if (empty($lastName)) {
      $errors['lastName'] = 'Le nom ne peut pas être vide.';
    }
    if (empty($firstName)) {
      $errors['firstName'] = 'Le prénom ne peut pas être vide.';
    }
    if (empty($password)) {
      $errors['password'] = 'Le mot de passe ne peut pas être vide';
    }
    if (empty($confirmedPassword)) {
      $errors['confirmedPassword'] = 'La confirmation du mot de passe ne peut pas être vide';
    }
    if ($password !== $confirmedPassword) {
      $errors['matching password'] = 'les mots de passes ne correspondent pas';
    }

    if (empty($email)) {
      $errors['email'] = 'L\'adresse email ne peut pas être vide.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'L\'adresse email n\'est pas valide.';
    }

    $userRepository = new UserRepository;
    if ($userRepository->emailExists($email) > 0) {
      $errors['email'] = "L'email existe déjà en base de donnée";
    }

    return $errors;
  }
}
