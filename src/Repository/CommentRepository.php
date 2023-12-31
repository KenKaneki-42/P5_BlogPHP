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

  public function getAll(int $limit): ?array
  {
    $statement = $this->connection->prepare(
      "SELECT * FROM comment ORDER BY createdAt DESC LIMIT :limit"
    );

    $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
    $statement->execute();

    $comments = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $comments ?: null;
  }

  public function findById(int $id): ?Comment
  {
    $statement = $this->connection->prepare(
      "SELECT * FROM comment WHERE id = :id"
    );
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();

    return $statement->fetchObject(Comment::class) ?: null;
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

    $sql = "INSERT INTO comment ( content, moderate, status,createdAt, publishedAt, userId, postId)
    VALUES (:content, :moderate, :status,:createdAt, :publishedAt, :userId, :postId)";

    $statement = $this->connection->prepare($sql);

    if (!$statement) {
      throw new \RuntimeException("Error on preparation SQL statement.");
    }
    $statement->bindParam(':content', $content, PDO::PARAM_STR);
    $statement->bindParam(':moderate', $moderate, PDO::PARAM_BOOL);
    $statement->bindParam(':status', $status, PDO::PARAM_STR);
    $statement->bindParam(':createdAt', $createdAt, PDO::PARAM_STR);
    $statement->bindParam(':publishedAt', $publishedAt, PDO::PARAM_STR);
    $statement->bindParam(':userId', $userId, PDO::PARAM_STR);
    $statement->bindParam(':postId',$userId, PDO::PARAM_STR);
    // $statement->bindParam(':is_enabled', $isEnabled, PDO::PARAM_STR);

    $result = $statement->execute();

    // check execution succes
    if (!$result) {
      // $errorInfos = $statement->errorInfo(); foreach on errors array??
      throw new \RuntimeException("Error during execution of SQL statement.");
    }
  }
}
