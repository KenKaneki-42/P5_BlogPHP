<?php

namespace App\Entity;

use DateTime;
use Core\component\ConsoleIO;

class User extends AbstractEntity
{
  private ?int $id = null;
  private ?array $role = null;
  private ?string $firstname = null;
  private ?string $lastname = null;
  private ?string $email = null;
  private ?string $password = null;
  private ?string $token = null;
  private DateTime $createdAt;
  private DateTime $updatedAt;
  private bool $isEnabled = false;
  private bool $isValidated = false;
  private ?string $profilPicture = null;

  public function __construct(array $data = [])
  {
    $this->createdAt = new DateTime();
    $this->updatedAt = new DateTime();
    // $this->role = [];
    parent::__construct($data);
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(?int $id): void
  {
    $this->id = $id;
  }

  public function getRole(): ?array
  {
    return $this->role ?? ['ROLE_USER'];
  }

  public function setRole(?string $role): void
  {
    if ($role !== null) {
      $this->role = json_decode($role, true);
    }
  }

  public function setAsAdmin(User $user): void
  {
    if ($user) {
      if(!in_array('ROLE_ADMIN', $user->getRole()))
      {
        $this->role[] = 'ROLE_ADMIN';
      }
    }
  }
  public function getFirstname(): ?string
  {
    return $this->firstname;
  }

  public function setFirstname(string $firstname): void
  {
    $this->firstname = $firstname;
  }

  public function getLastname(): ?string
  {
    return $this->lastname;
  }

  public function setLastname(string $lastname): void
  {
    $this->lastname = $lastname;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): void
  {
    $this->email = $email;
  }

  public function getPassword(): ?string
  {
    return $this->password;
  }

  public function setPassword(string $password): void
  {
    $this->password = $password;
  }

  public function getToken(): ?string
  {
    return $this->token;
  }

  public function setToken(string $token): void
  {
    $this->token = $token;
  }

  public function getCreatedAt(): DateTime
  {
    return $this->createdAt;
  }

  public function setCreatedAt($createdAt): void
  {
    if (!$createdAt instanceof DateTime) {
      if (is_string($createdAt)) {
        $createdAt = new DateTime($createdAt);
      } else {
        throw  new \InvalidArgumentException('Invalid value provided for createdAt');
      }
    }
    $this->createdAt = $createdAt;
  }

  public function getUpdatedAt(): DateTime
  {
    return $this->updatedAt;
  }

  public function setUpdatedAt($updatedAt): void
  {
    if (!$updatedAt instanceof DateTime) {
      if (is_string($updatedAt)) {
        $updatedAt = new DateTime($updatedAt);
      } else {
        throw  new \InvalidArgumentException('Invalid value provided for createdAt');
      }
    }
    $this->updatedAt = $updatedAt;
  }

  public function getIsEnabled(): bool
  {
    return $this->isEnabled;
  }

  public function setIsEnabled(bool $isEnabled): void
  {
    $this->isEnabled = $isEnabled;
  }
  public function getIsValidated(): bool
  {
    return $this->isValidated;
  }
  public function setIsValidated(bool $isValidated): void
  {
    $this->isValidated = $isValidated;
  }

  public function getProfilPicture(): ?string
  {
    return $this->profilPicture;
  }

  public function setProfilPicture(?string $profilPicture): void
  {
    $this->profilPicture = $profilPicture;
  }
  public function generateToken(): string
  {
    $randomBytes = bin2hex(random_bytes(32));
    $hashedToken = hash('sha256', $randomBytes);
    return $hashedToken;
  }

  // public function getConfirmationToken(): ?string
  // {
  //   return $this->confirmationToken;
  // }
}
