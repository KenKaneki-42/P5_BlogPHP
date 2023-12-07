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
  private DateTime $createdAt;
  private ?Datetime $updatedAt = null;
  private ?int $userId = null;
  // private bool $isEnabled;

  public function __construct(array $data = [])
  {
    $this->createdAt = new DateTime();
    $this->updatedAt = $this->createdAt;
    // $this->isEnabled = false;
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

  public function getCreatedAt(): DateTime
  {
    return $this->createdAt;
  }

  public function setCreatedAt(DateTime $createdAt): void
  {
    $this->createdAt = $createdAt;
  }

  public function getUpdatedAt(): ?DateTime
  {
    return $this->updatedAt;
  }

  public function setUpdatedAt(DateTime $updatedAt): void
  {
    $this->updatedAt = $updatedAt;
  }

  public function getUserId(): ?int
  {
    return $this->userId;
  }

  public function setUserId(int $userId): void
  {
    // $firstNameAuthor = $author->getFirstName();
    // $lastNameAuthor = $author->getLastName();
    // $this->author = sprintf("%s %s", $firstNameAuthor, $lastNameAuthor);
    $this->userId = $userId;
  }

  // public function getIsEnabled(): bool
  // {
  //   return $this->isEnabled;
  // }

  // public function setIsEnabled(bool $isEnabled): void
  // {
  //   $this->isEnabled = $isEnabled;
  // }
}
