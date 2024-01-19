<?php
// declare(strict_types=1);
// src/model/comment.php
namespace App\Entity;

use DateTime;

class Comment extends AbstractEntity
{
  private ?int $id = null;
  private ?string $content = null;
  private bool $moderate = false;
  private ?string $status = null;
  private DateTime $createdAt;
  private ?DateTime $publishedAt = null;
  private ?int $postId = null;
  private ?int $userId = null;


  public function __construct(array $data = [])
  {
    $this->createdAt = new DateTime();
    $this->moderate = false;
    $this->status = 'pending';
    parent::__construct($data);
  }

  public function getId(): ?int
  {
    return $this->id;
  }
  public function getContent(): ?string
  {
    return $this->content;
  }

  public function setContent(string $content): void
  {
    $this->content = $content;
  }

  public function getModerate(): bool
  {
    return $this->moderate;
  }

  public function setModerate(bool $moderate): void
  {
    $moderate = true ? $this->moderate = $moderate : $this->moderate = false;
  }

  public function getStatus(): ?string
  {
    return $this->status;
  }

  public function setStatus(?string $status): void
  {
    $this->status = $status;
  }
  public function getCreatedAt(): DateTime
  {
    return $this->createdAt;
  }

  public function setCreatedAt(DateTime $createdAt): void
  {
    $this->createdAt = $createdAt;
  }

  public function getPublishedAt(): ?DateTime
  {
    return $this->publishedAt;
  }

  public function setPublishedAt($publishedAt): void
  {
    $this->publishedAt = $publishedAt;
  }
  public function getPostId(): ?int
  {
    return $this->postId;
  }

  public function setPostId(?int $postId): void
  {
    $this->postId = $postId;
  }
  public function getUserId(): ?int
  {
    return $this->userId;
  }

  public function setUserId(int $userId): void
  {
    $this->userId = $userId;
  }
}
