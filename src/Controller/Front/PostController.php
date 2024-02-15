<?php

namespace App\Controller\Front;

use Core\component\AbstractController;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Core\database\ConnectionDb; // Import the ConnectionDb class
use Core\Router;

class PostController extends AbstractController
{

  protected PostRepository $postRepository;
  protected CommentRepository $commentRepository;
  protected UserRepository $userRepository;
  public function __construct()
  {
    parent::__construct();
    $this->postRepository = new PostRepository();
    $this->commentRepository = new CommentRepository();
    $this->userRepository = new UserRepository;
  }

  public function index()
  {
    $posts = $this->postRepository->getAll(100);
    return $this->render("front/posts", ['posts' => $posts]);
  }

  public function show(int $id)
  {
    $post = $this->postRepository->findById($id);
    $comments = $this->commentRepository->findByPostId($id);

    // retrieve username from the comment by userId associate with the comment
    foreach ($comments as $comment) {
      $userId = $comment->getUserId();
      $user = $this->userRepository->findById($userId);
      $commentUsers[] = [
        'firstname' => $user->getFirstname(),
        'lastname' => $user->getLastname(),
      ];
    }


    return $this->render("front/post", [
      'post' => $post,
      'comments' => $comments,
      'commentUsers' => $commentUsers
    ]);
  }
}
