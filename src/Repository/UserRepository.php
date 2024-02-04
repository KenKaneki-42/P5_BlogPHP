<?php

namespace App\Repository;

use App\Entity\User;
use Core\database\ConnectionDb;
use PDO;

class UserRepository
{
  private PDO $connection;

  public function __construct()
  {
    $this->connection = ConnectionDb::getConnection();
  }

  public function getAll(int $limit): ?array
  {
    $statement = $this->connection->prepare(
      "SELECT * FROM user ORDER BY created_at DESC LIMIT :limit"
    );

    $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
    $statement->execute();

    // Utilisez FETCH_ASSOC car vous ne souhaitez pas remplir un objet User avec un tableau associatif
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Vérifiez si $users n'est pas vide avant de traiter les données
    // if ($users) {
    //     foreach ($users as &$user) {
    //         // PDO devrait automatiquement convertir la colonne role au format JSON
    //         // Vous pouvez laisser la colonne role en tant que chaîne dans votre classe User
    //         $user['role'] = json_decode($user['role'], true);
    //     }
    // }

    // var_dump($users);
    // die;

    return $users ?: null;
  }

  public function findById(int $id): ?User
{
    $statement = $this->connection->prepare(
        "SELECT * FROM user WHERE id = :id"
    );
    $statement->bindParam(":id", $id, PDO::PARAM_INT);
    $statement->execute();
    $userData = $statement->fetch(PDO::FETCH_ASSOC);
    // var_dump($userData);
    if (!$userData) {
        return null;
    }
    return new User($userData);
}

  public function save(User $user): void
  {
    $role = json_encode($user->getRole());
    $firstName = $user->getFirstName();
    $lastName = $user->getLastName();
    $email = $user->getEmail();
    $password = $user->getPassword();

    if(null === $user->getToken()){
      $newToken = $user->generateToken();
      $user->setToken($newToken);
    }

    $token = $user->getToken();
    $createdAt = $user->getCreatedAt()->format('Y-m-d H:i:s');
    $updatedAt = $user->getUpdatedAt() ? $user->getUpdatedAt()->format('Y-m-d H:i:s') : $createdAt;
    $isEnabled = $user->getIsEnabled();
    $isValidated = $user->getIsValidated();
    $profilPicture = $user->getProfilPicture();

    $sql = "INSERT INTO user (role, firstname, lastname, email, password, token, created_at, updated_at, is_enabled, is_validated, profil_picture)
          VALUES (:role, :firstname, :lastname, :email, :password, :token, :created_at, :updated_at, :is_enabled, :is_validated, :profil_picture)";

    $statement = $this->connection->prepare($sql);

    if (!$statement) {
      throw new \RuntimeException("Error on preparing SQL statement.");
    }
    $statement->bindParam(':role', $role, PDO::PARAM_STR);
    $statement->bindParam(':firstname', $firstName, PDO::PARAM_STR);
    $statement->bindParam(':lastname', $lastName, PDO::PARAM_STR);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->bindParam(':password', $password, PDO::PARAM_STR);
    $statement->bindParam(':token', $token, PDO::PARAM_STR);
    $statement->bindParam(':created_at', $createdAt, PDO::PARAM_STR);
    $statement->bindParam(':updated_at', $updatedAt, PDO::PARAM_STR);
    $statement->bindParam(':is_enabled', $isEnabled, PDO::PARAM_BOOL);
    $statement->bindParam(':is_validated', $isValidated, PDO::PARAM_BOOL);
    $statement->bindParam(':profil_picture', $profilPicture, PDO::PARAM_STR);
    $result = $statement->execute();

    if (!$result) {
      throw new \RuntimeException("Error during SQL query execution.");
    }
  }

}
