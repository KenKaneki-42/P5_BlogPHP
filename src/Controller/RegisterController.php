<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Repository\UserRepository;
use App\Entity\User;
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
    if ($this->isSubmitted('submit') && $this->isValid($_POST)) {
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

      $validationErrors = $this->registerHandler->validateUserData($lastName, $firstName, $password, $_POST['confirm-password'], $email);

      if (!empty($validationErrors)) {
        // render error page or same form with indicate which field isn't adapt
        return $this->render('front/errors', ['errors' => $validationErrors]);
      }
      $this->userRepository->save($newUser);

      $this->registerHandler->sendEmailConfirmation($email, $newUser->getToken());

      $_SESSION['flash_message'] = 'Please confirm your email by clicking on the confirmation link sent to your email address.';
      return $this->redirect('connexion');
    }
    return $this->render("front/register");
  }

  public function login()
  {
    if ($this->isSubmitted('submit') && $this->isValid($_POST)) {
      $email = $_POST['email'];
      $password = $_POST['password'];

      $user = $this->userRepository->findByEmail($email);
      if ($user) {
        if (password_verify($password, $user->getPassword())) {
          $_SESSION['user_id'] = $user->getId();
          $_SESSION['user_firstname'] = $user->getFirstName();
          $_SESSION['user_lastname'] = $user->getLastName();
          $_SESSION['user_email'] = $user->getEmail();
          $_SESSION['user_role'] = $user->getRole();
          return $this->redirect('/homepage');
        }
      }
      $_SESSION['flash_message'] = 'Invalid email or password';
      return $this->redirect('connexion');
    }
    return $this->render("front/login");
  }

  public function logout()
  {
    session_destroy();
    return $this->redirect('connexion');
  }

  public function validationToken(string $token)
  {
    $errors = [];
    $user = $this->userRepository->findByToken($token);

    if (!$user) {
      // FIXME : changement en base ok pour la validation du compte mais pourtant on a ce message d'erreur ...
      $errors[] = "L'utilisateur n'existe pas avec le token fourni.";
      return $this->render('front/errors', ['errors' => $errors]);
    }

    // Vérification de l'existence de l'utilisateur et de la validité du token
    if (($user->getIsValidated() === false) && ($user->getToken() === $token)) {
      $user->setIsValidated(true);
      $this->userRepository->save($user);
      // Redirection vers une page de confirmation ou de connexion

      return $this->redirect("connexion");
    }
  }

  public function forgotPassword(){
    if ($this->isSubmitted('submit') && $this->isValid($_POST)) {
      $email = $_POST['email'];
      $user = $this->userRepository->findByEmail($email);
      if ($user) {
        $this->registerHandler->sendEmailResetPassword($email, $user->getToken());
        $_SESSION['flash_message'] = "Merci de confirmer l'email en cliquant sur le lien de confirmation envoyé à votre adresse mail";
        return $this->redirect('connexion');
      }
      $_SESSION['flash_message'] = 'Invalid email or password';
      return $this->redirect('connexion');
    }
    return $this->render("front/forgot-password");
  }
}
