<?php

namespace App\Controller\Admin;

use App\Service\Handler\RegisterHandler;
use Core\Component\AbstractController;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Controller\RegisterController;

class UserController extends AbstractController
{
  private UserRepository $userRepository;
  private RegisterHandler $registerHandler;

  public function __construct()
  {
    parent::__construct();
    $this->userRepository = new UserRepository;
    $this->registerHandler = new RegisterHandler;
  }

  public function addUser()
  {
    if ($this->isSubmitted('submitUser') && $this->isValid($_POST)) {
      $lastName = $_POST['lastName'];
      $firstName = $_POST['firstName'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $confirmedPassword = $_POST['confirmed-password'];
      $validationErrors = $this->registerHandler->validateUserData($lastName, $firstName, $password, $confirmedPassword, $email);

      if (!empty($validationErrors)) {
        // render error page or same form with indicate which field isn't adapt
        return $this->render('#', ['errors' => $validationErrors]);
      }

      $user = new User($_POST);
      $this->userRepository->save($user);
      return $this->redirect('/homepage');
    }
  }
}
