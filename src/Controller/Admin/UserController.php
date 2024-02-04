<?php

namespace App\Controller\Admin;

use Core\Component\AbstractController;
use App\Entity\User;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
private UserRepository $userRepository;

public function __construct()
{
  parent::__construct();
  $this->userRepository = new UserRepository;
}

public function addUser()
{
  if ($this->isSubmitted('submitUser') && $this->isValid($_POST)){
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmedPassword = $_POST['confirmed-password'];
    $validationErrors = $this->validateUserData($lastName, $firstName, $password, $confirmedPassword);

    if (!empty($validationErrors)) {
      // render error page or same form with indicate which field isn't adapt
      return $this->render('#', ['errors' => $validationErrors]);
    }

    $user = new User($_POST);
    $this->userRepository->save($user);
    return $this->redirect('/homepage');
  }
}

public function validateUserData(string $lastName, string $firstName, string $password, string $confirmedPassword) {
    $errors = [];

    if (empty($lastName)) {
      $errors['lastName'] = 'Le nom ne peut pas être vide.';
    }
    if (empty($firstName)) {
      $errors['firstName'] = 'Le prénom ne peut pas être vide.';
    }
    if ($password !== $confirmedPassword){
      $errors['password'] = 'Les mots de passes ne correspondent pas';
    }

    return $errors;
}

}
