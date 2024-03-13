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

  public function index(): string
  {
    $csrfToken = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrfToken;
    $comments = $this->commentRepository->findAll(1000);
    return $this->render("/admin/comment/index", [
      "comments" => $comments,
      "csrf_token" => $csrfToken
    ]);
  }

  public function changeStatus(int $commentId, string $status): string
  {
    if ($this->isSubmitted('moderateComment') && $this->isValid($_POST)) {
      $comment = $this->commentRepository->findbyId($commentId);
      if ($comment) {
        $comment->setStatus($status);
        $comment->setModerate(true);
        $this->commentRepository->update($comment);
        $statusTranslated = ($status === "refused") ? "refusé" : "accepté";
        $this->addMessageFlash('info', 'Commentaire est ' . $statusTranslated);
        return $this->redirect("/admin/commentaires");
      } else {
        $this->addMessageFlash("error", "Le commentaire spécifié n'existe pas.");
        return $this->redirect("/admin/commentaires");
      }
    }
    $this->addMessageFlash("error", "Une erreur est survenue lors de la soumission du formulaire.");
    return $this->redirect("/admin/commentaires");
  }
}
