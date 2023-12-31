<?php

namespace App\Fixtures;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Core\component\ConsoleIO;

class PostFixtures extends AbstractFixturesFactory

{
  public ConsoleIO $io;
  public function __construct(array $attributes = [])
  {
    $this->io = new ConsoleIO;
    parent::__construct($attributes);
  }

  public function load(): int|null
  {
    // $this->io->writeLine('Start of loading a new post.');
    $postRepository = new PostRepository();
    $userRepository = new UserRepository();
    $users = $userRepository->getAll(5);

    $randomIndex = array_rand($users);
    $randomUser = $users[$randomIndex];
    $randomUserId = $randomUser['id'];
    $this->io->writeLine(sprintf('random userId selected is: %d', $randomUserId));

    if ($randomUser !== null) {

      $post = new Post([
        'title' => $this->faker->title,
        'content' => $this->faker->paragraph,
        'tagline' => $this->faker->text(50),
        'userId' => $randomUserId
      ]);
      $postRepository->save($post);
      $userRepository = new UserRepository();
      $lastPostId = $postRepository->getAll(1)[0]['id'];
      return $lastPostId;
    } else {
      $this->io->writeLine('Could not retrieve a valid userId. Post creation aborted.');
      return null;
    }
  }
}
