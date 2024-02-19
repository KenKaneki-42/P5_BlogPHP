<?php
// declare(strict_types=1);
namespace App\Entity;

use DateTime;

class Post extends AbstractEntity
{
  private ?int $id = null;
  private ?string $title = null;
  private ?string $content = null;
  private ?string $tagline = null;
  private $createdAt;
  private $updatedAt = null;
  private ?int $userId = null;
  private ?string $slug;

  public function __construct(array $data = [])
  {
    $this->createdAt = new DateTime();
    $this->updatedAt = $this->createdAt;
    parent::__construct($data);
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(string $title): void
  {
    $this->title = $title;
  }

  public function getContent(): ?string
  {
    return $this->content;
  }

  public function setContent(string $content): void
  {
    $this->content = $content;
  }

  public function updateContent(string $newContent): void
  {
    $this->content = $newContent;
    $this->updatedAt = new DateTime();
  }

  public function getTagline(): ?string
  {
    return $this->tagline;
  }

  public function setTagline(string $tagline): void
  {
    $this->tagline = $tagline;
  }

  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  public function setCreatedAt($createdAt): void
  {
    $this->createdAt = $createdAt;
  }

  public function getUpdatedAt()
  {
    return $this->updatedAt;
  }

  public function setUpdatedAt($updatedAt): void
  {
    $this->updatedAt = $updatedAt;
  }

  public function getUserId(): ?int
  {
    return $this->userId;
  }

  public function setUserId(int $userId): void
  {
    $this->userId = $userId;
  }

  public function getSlug(): ?string
  {
    return $this->slug;
  }

  public function setSlug(string $slug): void
  {
    $this->slug = $slug;
  }
}
