<?php

namespace App\Repository;

use App\Entity\Post;
use Core\database\ConnectionDb;
use PDO;
use RuntimeException;

class PostRepository
{
  private PDO $connection;

  public function __construct()
  {
    $this->connection = ConnectionDb::getConnection();
  }

  public function getAll(int $limit): ?array
  {
    $statement = $this->connection->prepare(
      "SELECT
        id,
        title,
        content,
        tagline,
        user_id as userId,
        updated_at as updatedAt,
        created_at as createdAt
      FROM post ORDER BY created_at DESC LIMIT :limit"
    );
    $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
    $statement->execute();

    $posts = $statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Post::class);

    return $posts ?: null;
  }

  public function findById(int $id, bool $commentsPrensence = false): ?Post
  {
    $statement = $this->connection->prepare(
      "SELECT
        id,
        title,
        content,
        tagline,
        user_id as userId,
        updated_at as updatedAt,
        created_at as createdAt
      FROM post WHERE id = :id"
    );
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Post::class);

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
    $statement->bindValue(':slug', $post->getSlug(), PDO::PARAM_STR);
    $result = $statement->execute();

    // check execution succes
    if (!$result) {
      throw new RuntimeException("Error during execution of SQL statement.");
    }
  }

  public function persistCreate(Post $post): void
  {
    $post->setUserId($_SESSION['user_id']);
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
  }

  public function persistUpdate(Post $post, array $data): void
  {
    if (!$post) {
      // handle when we can't retrieve the post
      throw new RuntimeException("Post with ID " . $post->getId() . "not found.");
    }

    // update properties of the post
    $post->setTitle($data['title']);
    $post->setContent($data['content']);
    $post->setTagline($data['tagline']);
    $post->setUserId($_SESSION['user_id']);
    $post->setUpdatedAt(new \DateTime());
    $post->setSlug(strtolower(str_replace(' ', '-', $post->getTitle())));

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
    $slug = strtolower(str_replace(' ', '-', $post->getTitle()));
    $post->setSlug($slug);

    $sql = "UPDATE post
            SET title = :title,
                content = :content,
                tagline = :tagline,
                updated_at = :updatedAt,
                user_id = :userId,
                slug = :slug
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
    $statement->bindValue(':slug', $slug, PDO::PARAM_STR);

    $result = $statement->execute();

    if (!$result) {
      throw new RuntimeException("Error during execution of SQL update statement.");
    }
  }

  public function delete(int $idPost): void
  {
    $sql = "DELETE FROM post
            WHERE id = :id";

    $statement = $this->connection->prepare($sql);
    $statement->bindValue(':id', $idPost, PDO::PARAM_INT);
    $result = $statement->execute();
    $statement->closeCursor();
    if (!$result) {
      throw new RuntimeException("Error during execution of SQL delete statement.");
    }
  }
}
