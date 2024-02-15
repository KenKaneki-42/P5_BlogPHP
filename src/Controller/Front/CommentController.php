<?php

namespace App\Controller\Front;

use App\Repository\CommentRepository;
use App\Entity\Comment;
use Core\component\AbstractController;
use App\Service\Handler\CommentHandler;
class  CommentController extends AbstractController
{

  private CommentRepository $commentRepository;
  // private UserRepository $userRepository;
  // private PostRepository $postRepository;

  private CommentHandler $commentHandler;
  public function __construct()
  {
    parent::__construct();
    $this->commentRepository = new CommentRepository;
    $this->commentHandler = new CommentHandler;
  }

  public function index(int $postId): void
  {
    // TODO : $this->isAdmin();
    // $posts = $this->commentRepository->getAll(10);
    // $this->render('home/index.html.twig', ['posts' => $posts]);
  }

  public function show(int $id): void
  {
    // $post = $this->commentRepository->findById($id);
    // fetch all comments link to the previous post
    // $comments = $this->getComments($id);

    // $this->render('post/show.html.twig', ['post' => $post, 'comments' => $comments]);
  }

  public function create(int $postId)
  {
    $route = sprintf('/articles/%s', $postId);

    if ($this->isSubmitted('submitComment') && $this->isValid($_POST)) {
      $content = $_POST['content']?? '';
      $postId = $_POST['postId']?? 0;
      $userId = $_SESSION['user_id']?? 0;
      $validationErrors = $this->commentHandler->validateCommentData($content, $postId, $userId);

      if (!empty($validationErrors)) {
        // render error page or same form with indicate which field isn't adapt
        return $this->render('admin/post/errors', ['errors' => $validationErrors]);
      }
      $comment =  new Comment($_POST);
      $comment->setUserId($userId);
      $this->commentRepository->flush($comment);
      // TODO add success message for creation popup
      return $this->redirect($route); // to modify
    }
    return $this->redirect($route);
  }

  public function update(string $id, Comment $comment): void
  {
    return;
  }


  public function delete(int $id): void
  {
  }
}
// function addComment(string $post, array $input)
// {
//   $author = null;
//   $comment = null;
//   if (!empty($input['author']) && !empty($input['comment'])) {
//     $author = $input['author'];
//     $comment = $input['comment'];
//   } else {
//     die('Les données du formulaire sont invalides.');
//   }

//   $success = createComment($post, $author, $comment);
//   if (!$success) {
//     die('Impossible d\'ajouter le commentaire !');
//   } else {
//     header('Location: index.php?action=post&id=' . $post);
//   }
// }
