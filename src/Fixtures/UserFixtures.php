<?php

namespace App\Fixtures;

use App\Entity\User;
use App\Repository\UserRepository;

class UserFixtures extends AbstractFixturesFactory

{
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
  }

  public function load(): int|null
  {
    $userRepository = new UserRepository();

    $user = new User([
      'firstname' => $this->faker->firstName,
      'lastname' => $this->faker->lastName,
      'email' => $this->faker->email,
      'password' => $this->faker->password,
      'token' => $this->faker->sha256,
      'profilPicture' => $this->faker->imageUrl(200, 200, 'people')
    ]);
    $userRepository->save($user);
    $lastUserId = $userRepository->getAll(1)[0]['id'];

    return $lastUserId;
  }
}
