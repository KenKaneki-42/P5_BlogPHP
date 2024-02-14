<?php

namespace App\Controller\Admin;

use App\Repository\CommentRepository;
use App\Entity\Comment;
use Core\component\AbstractController;

class  CommentController extends AbstractController
{

  private CommentRepository $commentRepository;
  private UserRepository $userRepository;
  private PostRepository $postRepository;
  public function __construct(CommentRepository $commentRepository)
  {
    parent::__construct();
    $this->commentRepository = $commentRepository;
  }

  public function index(): void
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

  public function create() : Comment
  {
    if ($this->isSubmitted('submitComment') && $this->isValid($_POST)) {
      $content = $_POST['content']?? '';
      $postId = $_POST['postId']?? 0;
      $userId = $_POST['userId']?? 0;
      $validationErrors = $this->validateCommentData($content, $postId, $userId);

      if (!empty($validationErrors)) {
        // render error page or same form with indicate which field isn't adapt
        return $this->render('admin/post/errors', ['errors' => $validationErrors]);
      }
      $comment = $this->commentRepository->persistCreate($content, $postId, $userId);
      // TODO add success message for creation popup

      return $comment;
    }
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
