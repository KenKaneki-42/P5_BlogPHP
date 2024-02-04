<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Repository\UserRepository;
use App\Entity\User;
use Core\database\ConnectionDb; // Import the ConnectionDb class
use Core\Router;
use App\Service\Handler\RegisterHandler;

class RegisterController extends AbstractController
{

  protected UserRepository $userRepository;
  protected RegisterHandler $registerHandler;

  public function __construct()
  {
    parent::__construct();
    $this->userRepository = new UserRepository();
    $this->registerHandler = new RegisterHandler();
  }

  public function register()
  {
    if ($this->isSubmitted('submit') && $this->isValid($_POST))
    {
      $firstName = $_POST['firstname'];
      $lastName = $_POST['lastname'];
      $password = $_POST['password'];
      $email = $_POST['email'];
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      $newUser = new User();
      $newUser->setFirstName($firstName);
      $newUser->setLastName($lastName);
      $newUser->setPassword($hashedPassword);
      $newUser->setEmail($email);

      $validationErrors = $this->registerHandler->validateUserData($lastName, $firstName, $password, $_POST['confirm-password'] , $email);

      if (!empty($validationErrors)) {
        // render error page or same form with indicate which field isn't adapt
        return $this->render('registration/errors', ['errors' => $validationErrors]);
      }

      $this->userRepository->save($newUser);
      $this->redirect("connexion");
    }
    return $this->render("front/register");
  }

}
