<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Repository\UserRepository;
use App\Entity\User;
use Core\database\ConnectionDb; // Import the ConnectionDb class
use Core\Router;

class RegisterController extends AbstractController {

  protected UserRepository $userRepository;

  public function __construct()
  {
    parent::__construct();
    $this->userRepository = new UserRepository();
  }

  public function register()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // $username = $_POST['username'];
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

      // $validationErrors = $this->validateUserData($firstName, $lastName, $hashedPassword, $email);

      // Connexion à la base de données et enregistrement de l'utilisateur (utilise des requêtes préparées pour des raisons de sécurité)
      // $pdo = new PDO("mysql:host=localhost;dbname=nom_de_ta_base_de_donnees", "utilisateur", "mot_de_passe");
      $this->userRepository->save($newUser);
      // $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
      // $stmt->execute([$username, $hashedPassword, $email]);

      // Redirection vers une page de confirmation ou autre
      header('Location: registration_success.php');
      exit;
  }
    return $this->render("front/register",['user'=> $newUser]);
  }

}
