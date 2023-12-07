<?php

namespace App\Fixtures;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Core\component\ConsoleIO;

class CommentFixtures extends AbstractFixturesFactory

{
  // private Comment $comment;
  public ConsoleIO $io;
  public function __construct(array $attributes = [])
  {
    $this->io = new ConsoleIO;
    parent::__construct($attributes);
  }

  public function load(): int|null
  {
    $commentRepository = new CommentRepository();
    $postRepository = new PostRepository();
    $userRepository = new UserRepository();

    $posts = $postRepository->getAll(5);
    $users = $userRepository->getAll(5);

    // select a random post to associate the comment
    $randomPostIndex = array_rand($posts);
    $randomPost = $posts[$randomPostIndex];
    $randomPostId = $randomPost['id'];
    //select a random user to associate the author of the comment
    $randomUserIndex = array_rand($users);
    $randomUser = $users[$randomUserIndex];
    $randomUserId = $randomUser['id'];

    if(($randomUser !== null) && ($randomPost !== null))
    {
      // $commentRepository = new CommentRepository();
      $comment =  new Comment([
      'content' => $this->faker->paragraph,
      'userId' => $randomUserId,
      'postId' => $randomPostId
      ]);
      $commentRepository->save($comment);
      $commentRepository = new CommentRepository();
      $lastCommentId = $commentRepository->getAll(1)[0]['id'];
      return $lastCommentId;
    } else {
      $this->io->writeLine('Could not retrieve a valid userId and postId. Post creation aborted.');
      return null;
    }

  }
}
