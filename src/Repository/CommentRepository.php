<?php

namespace App\Repository;

use App\Entity\Comment;
use Core\database\ConnectionDb;
use PDO;

class CommentRepository
{
  private PDO $connection;

  public function __construct()
  {
    $this->connection = ConnectionDb::getConnection();
  }

  public function findAll(int $limit): ?array
  {
    $statement = $this->connection->prepare(
      "SELECT * FROM comment ORDER BY created_at DESC LIMIT :limit"
    );

    $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
    $statement->execute();

    $comments = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $comments ?: null;
  }

  // V2 with pagination
  // public function findAll(int $limit, int $offset): ?array
  // {
  //     try {
  //         $statement = $this->connection->prepare(
  //           "SELECT * FROM comment ORDER BY created_at DESC LIMIT :limit OFFSET :offset"
  //         );

  //         $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
  //         $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
  //         $statement->execute();

  //         $comments = $statement->fetchAll(PDO::FETCH_ASSOC);
  //         return $comments ?: null;
  //     } catch (PDOException $e) {
  // Log l'erreur ou affiche un message d'erreur générique à l'utilisateur
  // error_log($e->getMessage());
  //         return null; // Ou gérer l'erreur d'une manière qui convient à votre application
  //     }
  // }

  public function findById(int $id): ?Comment
  {
    $statement = $this->connection->prepare(
      "SELECT
      id,
      content,
      moderate,
      status,
      created_at as createdAt,
      published_at as publishedAt,
      post_id as postId,
      user_id as userId
      FROM comment WHERE id = :id"
    );
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();

    return $statement->fetchObject(Comment::class) ?: null;
  }

  public function findByPostId(int $postId): array
  {
    $statement = $this->connection->prepare(
      "SELECT
        id,
        content,
        moderate,
        status,
        created_at as createdAt,
        published_at as publishedAt,
        post_id as postId,
        user_id as userId
      FROM comment WHERE post_id = :postId ORDER BY created_at DESC"
    );
    $statement->bindValue(':postId', $postId, PDO::PARAM_INT);
    $statement->execute();

    $comments = $statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Comment::class);

    return $comments ?: [];
  }

  public function findValidatedByPostId(int $postId): array
  {
    $statement = $this->connection->prepare(
      "SELECT
            id,
            content,
            moderate,
            status,
            created_at as createdAt,
            published_at as publishedAt,
            post_id as postId,
            user_id as userId
        FROM comment
        WHERE post_id = :postId
        AND status = 'accepted'
        ORDER BY created_at DESC"
    );
    $statement->bindValue(':postId', $postId, PDO::PARAM_INT);
    $statement->execute();

    $comments = $statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Comment::class);

    return $comments ?: [];
  }


  public function save(Comment $comment): void
  {
    $content = $comment->getContent();
    $moderate = $comment->getModerate();
    $status = $comment->getStatus();
    $createdAt = $comment->getCreatedAt()->format('Y-m-d H:i:s');
    $publishedAt = $comment->getPublishedAt();
    $userId = $comment->getUserId();
    $postId = $comment->getPostId();

    $sql = "INSERT INTO comment ( content, moderate, status,created_at, published_at, user_id, post_id)
    VALUES (:content, :moderate, :status,:createdAt, :publishedAt, :userId, :postId)";

    $statement = $this->connection->prepare($sql);

    if (!$statement) {
      throw new \RuntimeException("Error on preparation SQL statement.");
    }
    $statement->bindValue(':content', $content, PDO::PARAM_STR);
    $statement->bindValue(':moderate', $moderate, PDO::PARAM_BOOL);
    $statement->bindValue(':status', $status, PDO::PARAM_STR);
    $statement->bindValue(':createdAt', $createdAt, PDO::PARAM_STR);
    $statement->bindValue(':publishedAt', $publishedAt, PDO::PARAM_STR);
    $statement->bindValue(':userId', $userId, PDO::PARAM_INT);
    $statement->bindValue(':postId', $postId, PDO::PARAM_INT);

    $result = $statement->execute();

    // check execution succes
    if (!$result) {
      throw new \RuntimeException("Error during execution of SQL statement.");
    }
  }

  public function update(Comment $comment): void
  {
    $id = $comment->getId();
    $content = $comment->getContent();
    $status = $comment->getStatus();
    $moderate = $comment->getModerate();
    $createdAt = $comment->getCreatedAt()->format('Y-m-d H:i:s');
    $publishedAt = $comment->getPublishedAt();
    $userId = $comment->getUserId();
    $postId = $comment->getPostId();

    $sql = "UPDATE comment SET
                content = :content,
                status = :status,
                moderate = :moderate,
                created_at = :createdAt,
                published_at = :publishedAt,
                user_id = :userId,
                post_id = :postId
            WHERE id = :id";

    $statement = $this->connection->prepare($sql);

    if (!$statement) {
      throw new \RuntimeException("Error on preparation SQL statement.");
    }

    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->bindValue(':content', $content, PDO::PARAM_STR);
    $statement->bindValue(':status', $status, PDO::PARAM_STR);
    $statement->bindValue(':moderate', $moderate, PDO::PARAM_BOOL);
    $statement->bindValue(':createdAt', $createdAt, PDO::PARAM_STR);
    $statement->bindValue(':publishedAt', $publishedAt, PDO::PARAM_STR);
    $statement->bindValue(':userId', $userId, PDO::PARAM_INT);
    $statement->bindValue(':postId', $postId, PDO::PARAM_INT);

    $result = $statement->execute();

    if (!$result) {
      throw new \RuntimeException("Error during execution of SQL statement.");
    }
  }

  public function delete(int $idComment): void
  {
    $sql = "DELETE FROM comment
            WHERE id = :id";

    $statement = $this->connection->prepare($sql);
    $statement->bindValue(':id', $idComment, PDO::PARAM_INT);
    $result = $statement->execute();

    if (!$result) {
      throw new \RuntimeException("Error during execution of SQL delete statement.");
    }
  }

  public function flush(Comment $comment): void
  {
    $sql = "INSERT INTO comment
            SET content = :content,
                moderate = :moderate,
                status = :status,
                created_at = :createdAt,
                published_at = :publishedAt,
                post_id = :postId,
                user_id = :userId
                ";

    $statement = $this->connection->prepare($sql);

    if (!$statement) {
      throw new \RuntimeException("Error on preparation SQL statement.");
    }
    $statement->bindValue(':content', $comment->getContent(), PDO::PARAM_STR);
    $statement->bindValue(':moderate', $comment->getModerate(), PDO::PARAM_BOOL);
    $statement->bindValue(':status', $comment->getStatus(), PDO::PARAM_STR);
    $statement->bindValue(':createdAt', $comment->getCreatedAt()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
    $statement->bindValue(':publishedAt', $comment->getPublishedAt(), PDO::PARAM_STR);
    $statement->bindValue(':postId', $comment->getPostId(), PDO::PARAM_INT);
    $statement->bindValue(':userId', $comment->getUserId(), PDO::PARAM_INT);

    $result = $statement->execute();

    // check execution succes
    if (!$result) {
      throw new \RuntimeException("Error during execution of SQL statement.");
    }
  }
}
