<?php

namespace App\Controller;

use Core\Component\AbstractController;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Service\Handler\RegisterHandler;
use App\Service\UserDataValidator;

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

  public function register(): string
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
      $validationErrors = UserDataValidator::validateUserData($lastName, $firstName, $password, $_POST['confirm-password'], $email);
      if (!empty($validationErrors)) {
        // render error page or same form with indicate which field isn't adapt
        return $this->render('front/errors', ['errors' => $validationErrors]);
      }
      $this->userRepository->save($newUser);

      $this->registerHandler->sendEmailConfirmation($email, $newUser->getToken());

      $this->addMessageFlash("info", "Merci de confirmer votre inscription en cliquant sur le lien de confirmation envoyé par email.");
      return $this->redirect('/connexion');
    }
    return $this->render("front/register");
  }

  public function login(): string
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
          $this->addMessageFlash('info', 'authentification réussie');
          return $this->redirect('/homepage');
        }
      }
      $this->addMessageFlash('warning', 'email ou mot de passe invalide');
      return $this->redirect('/connexion');
    }
    return $this->render("front/login");
  }

  public function logout(): string
  {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_firstname']);
    unset($_SESSION['user_lastname']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_role']);

    $this->addMessageFlash('info', 'déconnexion réussie');
    return $this->redirect('/connexion');
  }

  public function validationToken(string $token): string
  {
    $errors = [];
    $user = $this->userRepository->findByToken($token);

    if (!$user) {
      $errors[] = "L'utilisateur n'existe pas avec le token fourni.";
      return $this->render('front/errors', ['errors' => $errors]);
    }

    if (!$user->getIsValidated() && $user->getToken() === $token) {
      $user->setIsValidated(true);
      $this->userRepository->save($user);
      $this->addMessageFlash('success', 'Votre compte a été validé avec succès. Vous pouvez maintenant vous connecter.');
      return $this->redirect("/connexion");
    } else {
      $this->addMessageFlash('info', 'Votre compte est déjà validé ou le token ne correspond pas. Veuillez vous connecter.');
      return $this->redirect("/connexion");
    }
  }


  public function forgotPassword(): string
  {
    if ($this->isSubmitted('submit') && $this->isValid($_POST)) {
      $email = $_POST['email'];
      $user = $this->userRepository->findByEmail($email);
      if ($user) {
        $this->registerHandler->sendEmailResetPassword($email, $user->getToken());
      }
      $this->addMessageFlash("info", "Merci de confirmer l'email en cliquant sur le lien de confirmation envoyé à votre adresse mail");
      return $this->redirect('/connexion');
    }
    return $this->render("front/forgotPassword");
  }

  public function validationNewPassword(string $token): string
  {
    $errors = [];
    $user = $this->userRepository->findByToken($token);

    if (!$user) {
      $errors[] = "L'utilisateur n'existe pas avec le token fourni.";
      return $this->render('front/errors', ['errors' => $errors]);
    }

    if ($this->isSubmitted('submit') && $this->isValid($_POST)) {

      $password = $_POST['password'];
      $confirmPassword = $_POST['confirm-password'];

      if (empty($password) || empty($confirmPassword)) {
        $errors['password'] = 'Le mot de passe ne peut pas être vide';
        return $this->render('front/errors', ['errors' => $errors]);
      }
      if ($password !== $confirmPassword) {
        $errors['matching password'] = 'les mots de passes ne correspondent pas';
        return $this->render('front/errors', ['errors' => $errors]);
      }

      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $user->setPassword($hashedPassword);

      $this->userRepository->save($user);

      $this->registerHandler->sendEmailConfirmChangementPassword($user->getEmail());
      $this->addMessageFlash('info', 'un email vous a été envoyé pour confirmer le changement de mot de passe');
      return $this->redirect('/connexion');
    }
    return $this->render("front/newPassword", ["token" => $token]);
  }
}
