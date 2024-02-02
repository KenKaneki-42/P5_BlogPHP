<?php

namespace App\Repository;

use App\Entity\Post;
use Core\database\ConnectionDb;
use PDO;
use RuntimeException;

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
      "SELECT * FROM post ORDER BY created_at DESC LIMIT :limit"
    );

    $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
    $statement->execute();

    $posts = $statement->fetchAll(PDO::FETCH_ASSOC);
    // TODO: $posts = array_reverse($posts) ne fonctionne pas :/

    return $posts ?: null;
  }

  public function findById(int $id): ?Post
  {

    $statement = $this->connection->prepare(
      "SELECT * FROM post WHERE id = :id"
    );
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_CLASS, Post::class);
    return $statement->fetch() ?: null;
  }

  public function save(Post $post): void
  {
    $updatedAt = $post->getUpdatedAt() ? $post->getUpdatedAt()->format('Y-m-d H:i:s') : $post->getCreatedAt()->format('Y-m-d H:i:s');
    $sql = "INSERT INTO post (title, content, tagline, created_at, updated_at, user_id, slug)
          VALUES (:title, :content, :tagline, :createdAt, :updatedAt, :userId, :slug)";
    $statement = $this->connection->prepare($sql);

    if (!$statement) {
      throw new RuntimeException("Error on preparation SQL statement.");
    }
    $statement->bindValue(':title', $post->getTitle(), PDO::PARAM_STR);
    $statement->bindValue(':content', $post->getContent(), PDO::PARAM_STR);
    $statement->bindValue(':tagline', $post->getTagline(), PDO::PARAM_STR);
    $statement->bindValue(':createdAt', $post->getCreatedAt()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
    $statement->bindValue(':updatedAt', $updatedAt, PDO::PARAM_STR);
    $statement->bindValue(':userId', $post->getUserId(), PDO::PARAM_STR);
    // $statement->bindValue(':is_enabled', $isEnabled, PDO::PARAM_STR);
    $statement->bindValue(':slug', $post->getSlug(), PDO::PARAM_STR);
    $result = $statement->execute();

    // check execution succes
    if (!$result) {
      // $errorInfos = $statement->errorInfo(); foreach on errors array??
      throw new RuntimeException("Error during execution of SQL statement.");
    }
  }

  public function persistCreate(Post $post): void
  {
    // TODO  set userId ( modify it when authentication system i)
    $_SESSION['user_id'] = 4;

    $post->setUserId($_SESSION['user_id']);
    // send to save it in DB
    $this->flushCreate($post);
  }

  private function flushCreate(Post $post): void
  {
    $sql = "INSERT INTO post
            SET title = :title,
                content = :content,
                tagline = :tagline,
                created_at = :createdAt,
                updated_at = :updatedAt,
                user_id = :userId,
                slug = :slug
            ";
    $slug = strtolower(str_replace(' ', '-', $post->getTitle()));
    $post->setSlug($slug);

    $statement = $this->connection->prepare($sql);

    if (!$statement) {
      throw new RuntimeException("Error on preparation SQL statement.");
    }

    $statement->bindValue(':title', $post->getTitle(), PDO::PARAM_STR);
    $statement->bindValue(':content', $post->getContent(), PDO::PARAM_STR);
    $statement->bindValue(':tagline', $post->getTagline(), PDO::PARAM_STR);
    $statement->bindValue(':createdAt', $post->getCreatedAt()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
    $statement->bindValue(':updatedAt', $post->getUpdatedAt()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
    $statement->bindValue(':userId', $post->getUserId(), PDO::PARAM_INT);
    $statement->bindValue(':slug', $post->getSlug(), PDO::PARAM_STR);

    $result = $statement->execute();

    // Vérifiez si la mise à jour s'est bien déroulée
    if (!$result) {
      throw new RuntimeException("Error during execution of SQL update statement.");
    }
    // pop up de valitation?
  }

  public function persistUpdate(Post $post, array $data): void
  {
    // TODO  set userId ( modify it when authentication system i)
    $_SESSION['user_id'] = 4;

    if (!$post) {
      // handle when we can't retrieve the post
      throw new RuntimeException("Post with ID " . $post->getId() . "not found.");
    }

    // update properties of the post
    $post->setTitle($data['title']);
    $post->setContent($data['content']);
    $post->setTagline($data['tagline']);
    // $post->setUserId($data['userId']);
    // TODO  replace me
    $post->setUserId($_SESSION['user_id']);
    $post->setUpdatedAt(new \DateTime());
    // $post->setSlug(strtolower(str_replace(' ', '-', $post->getTitle())));

    // send to save it in DB
    $this->flushUpdate($post);
  }

  private function flushUpdate(Post $post): void
  {
    $id = $post->getId();
    $title = $post->getTitle();
    $content = $post->getContent();
    $tagline = $post->getTagline();
    $updatedAt = $post->getUpdatedAt()->format('Y-m-d H:i:s');
    $userId = $post->getUserId();

    $sql = "UPDATE post
            SET title = :title,
                content = :content,
                tagline = :tagline,
                updated_at = :updatedAt,
                user_id = :userId
            WHERE id = :id";

    $statement = $this->connection->prepare($sql);

    if (!$statement) {
      throw new RuntimeException("Error on preparation SQL statement.");
    }

    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->bindValue(':title', $title, PDO::PARAM_STR);
    $statement->bindValue(':content', $content, PDO::PARAM_STR);
    $statement->bindValue(':tagline', $tagline, PDO::PARAM_STR);
    $statement->bindValue(':updatedAt', $updatedAt, PDO::PARAM_STR);
    $statement->bindValue(':userId', $userId, PDO::PARAM_INT);

    $result = $statement->execute();

    // Vérifiez si la mise à jour s'est bien déroulée
    if (!$result) {
      throw new RuntimeException("Error during execution of SQL update statement.");
    }
  }

  public function delete($idPost): void
  {
    $sql = "DELETE FROM post
            WHERE id = :id";

    $statement = $this->connection->prepare($sql);
    $statement->bindValue(':id', $idPost, PDO::PARAM_INT); // Assurez-vous que $idPost est du type INT, ajustez-le si nécessaire
    $result = $statement->execute();
    $statement->closeCursor();
    if (!$result) {
      throw new RuntimeException("Error during execution of SQL delete statement.");
    }

    // if ($result) {
    //   // Succès
    //   echo "<script>alert('Suppression réussie'); setTimeout(function(){ location.reload(); }, 1000);</script>";
    // } else {
    //   // Échec
    //   echo "<script>alert('Échec de la suppression');</script>";
    // }
  }
}
