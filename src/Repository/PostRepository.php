<?php

namespace App\Repository;

use App\Entity\Post;
use Core\database\ConnectionDb;
use PDO;

class PostRepository
{
  private PDO $connection;

  // Utilise une dépendance directe sur ConnectionDb.
  // Initialise la connexion directement dans le constructeur.
  // private ConnectionDB $connectionDb;

  public function __construct()
  {
    // $this->connection = $connectionDb->getConnection();
    $this->connection = ConnectionDb::getConnection();
  }

  public function getAll(int $limit): ?array
  {
    $statement = $this->connection->prepare(
      "SELECT * FROM post ORDER BY createdAt DESC LIMIT :limit"
    );

    $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
    $statement->execute();

    $posts = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $posts ?: null;
  }

  public function findById(int $id): ?Post
  {
    $statement = $this->connection->prepare(
      "SELECT * FROM post WHERE id = :id"
    );
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();

    return $statement->fetchObject(Post::class) ?: null;
  }

  public function save(Post $post): void
  {
    // var_dump($post);
    $title = $post->getTitle();
    $content = $post->getContent();
    $tagline = $post->getTagline();
    $createdAt = $post->getCreatedAt()->format('Y-m-d H:i:s');
    $updatedAt = $post->getUpdatedAt() ? $post->getUpdatedAt()->format('Y-m-d H:i:s') : null;
    $author = $post->getUserId();
    // var_dump($post);
    // die;
    // $isEnabled = $post->getIsEnabled();

    $sql = "INSERT INTO post (title, content, tagline, createdAt, updatedAt, userId)
          VALUES (:title, :content, :tagline, :createdAt, :updatedAt, :userId)";

    // var_dump($post);
    $statement = $this->connection->prepare($sql);

    if (!$statement) {
      throw new \RuntimeException("Error on preparation SQL statement.");
    }
    $statement->bindParam(':title', $title, PDO::PARAM_STR);
    $statement->bindParam(':content', $content, PDO::PARAM_STR);
    $statement->bindParam(':tagline', $tagline, PDO::PARAM_STR);
    $statement->bindParam(':createdAt', $createdAt, PDO::PARAM_STR);
    $statement->bindParam(':updatedAt', $updatedAt, PDO::PARAM_STR);
    $statement->bindParam(':userId', $author, PDO::PARAM_STR);
    // $statement->bindParam(':is_enabled', $isEnabled, PDO::PARAM_STR);

    $result = $statement->execute();

    // check execution succes
    if (!$result) {
      // $errorInfos = $statement->errorInfo(); foreach on errors array??
      throw new \RuntimeException("Error during execution of SQL statement.");
    }

  }
}
