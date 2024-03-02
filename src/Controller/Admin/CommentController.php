<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Core\Component\AbstractController;
use App\Repository\UserRepository;

class CommentController extends AbstractController
{
  private Comment $comment;
  private CommentRepository $commentRepository;
  private UserRepository $userRepository;
  public function __construct()
  {
    parent::__construct();
    $this->comment = new Comment;
    $this->commentRepository = new CommentRepository;
    $this->userRepository = new UserRepository;
    if (isset($_SESSION['user_email'])) {
      $user = $this->userRepository->findByEmail($_SESSION['user_email']);
    }
    $this->checkAdminAccess($user);
  }

  public function index()
  {
    $csrfToken = bin2hex(random_bytes(32));
    // TODO: Find a better way to do this
    $comments = $this->commentRepository->findAll(1000);
    return $this->render("/admin/comment/index", [
      "comments" => $comments,
      "csrf_token" => $csrfToken
    ]);
  }

  public function changeStatus(int $commentId, string $status)
  {
    if ($this->isSubmitted('moderateComment') && $this->isValid($_POST)) {
      $comment = $this->commentRepository->findbyId($commentId);
      // pouruqoi user id et post ID sont nulls ici?
      $comment->setStatus($status);
      $comment->setModerate(true);
      $this->commentRepository->update($comment);
      // $this->commentRepository->save($comment);
      $this->redirect("/admin/commentaires");
    }
  }

  // public function show(int $id):?array {
  //   $comment = $this->commentRepository->findById($id);
  //   return $comment;
  // }
}
