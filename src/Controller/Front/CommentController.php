<?php

namespace App\Controller\Front;

use App\Repository\CommentRepository;
use App\Entity\Comment;
use Core\component\AbstractController;
use App\Service\Handler\CommentHandler;
class  CommentController extends AbstractController
{
  private CommentRepository $commentRepository;

  private CommentHandler $commentHandler;
  public function __construct()
  {
    $this->commentRepository = new CommentRepository;
    $this->commentHandler = new CommentHandler;
  }

  public function create(int $postId): void
  {
    $route = sprintf('/articles/%s', $postId);

    if ($this->isSubmitted('submitComment') && $this->isValid($_POST)) {
      $content = $_POST['content']?? '';
      $postId = $_POST['postId']?? 0;
      $userId = $_SESSION['user_id']?? 0;
      $validationErrors = $this->commentHandler->validateCommentData($content, $postId, $userId);

      if (!empty($validationErrors)) {
        foreach ( $validationErrors as $error ) {
          $this->addMessageFlash('flash_message', $error );
        }
        $this->redirect($route);
      }
      $comment =  new Comment($_POST);
      $comment->setUserId($userId);
      $this->commentRepository->flush($comment);
      $this->addMessageFlash('flash_message', 'Votre commentaire est soumis à la validation');
      $this->redirect($route);
    }
    $this->redirect($route);
  }
}
