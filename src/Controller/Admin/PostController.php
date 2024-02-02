<?php

namespace App\Controller\Admin;

use Core\Component\AbstractController;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;

class PostController extends AbstractController
{
  private PostRepository $postRepository;
  private CommentRepository $commentRepository;

  public function __construct()
  {
    parent::__construct();
    $this->postRepository = new PostRepository;
    $this->commentRepository = new CommentRepository;
  }
  public function index()
  {
    $csrfToken = bin2hex(random_bytes(32));
    $posts = $this->postRepository->getAll(20);
    return $this->render('admin/post/index', [
      'posts' => $posts,
      'csrf_token' => $csrfToken
    ]);
  }
  public function showPost(int $id): void
  {
    $post = $this->postRepository->findById($id);

    if (!$post) {
      // handle
    }

    // Récupérez les commentaires liés au post (vous devez implémenter cette fonction)
    $comments = $this->commentRepository->getCommentsByPostId($id);

    // Utilisez la méthode render pour afficher la vue avec les données du post et des commentaires
    $this->render('admin/post/show', ['post' => $post, 'comments' => $comments]);
  }

  public function addPost()
  {
    if ($this->isSubmitted('submitPost') && $this->isValid($_POST)) {
      $title = $_POST['title'] ?? '';
      $content = htmlspecialchars($_POST['content']) ?? '';
      $tagline = $_POST['tagline'] ?? '';
      $userId = $_POST['userId'] ?? 0;
      $validationErrors = $this->validatePostData($title, $content, $tagline, $userId);

      if (!empty($validationErrors)) {
        // render error page or same form with indicate which field isn't adapt
        return $this->render('admin/post/errors', ['errors' => $validationErrors]);
      }

      $post = new Post($_POST);
      $this->postRepository->persistCreate($post);
      // TODO add success message for creation popup

      // return $this->redirect('admin_post_index');  Route not found: "/admin/admin_post_index/"
      return $this->redirect('/admin/articles');
    }
    return $this->render('admin/post/new');
  }
  public function edit(int $id)
  {
    $post = $this->postRepository->findById($id);

    if (!$post) {
      // handle with rendering in error page?
    }

    if ($this->isSubmitted('submitPost') && $this->isValid($_POST)) {
      $title = $_POST['title'] ?? '';
      $content = htmlspecialchars($_POST['content']) ?? '';
      $tagline = $_POST['tagline'] ?? '';
      $userId = $_POST['userId'] ?? 0;
      $validationErrors = $this->validatePostData($title, $content, $tagline, $userId);

      if (!empty($validationErrors)) {
        // render error page or same form with indicate which field isn't adapt
        return $this->render('admin/post/errors', ['errors' => $validationErrors]);
      }
      $this->postRepository->persistUpdate($post, $_POST);
      // TODO add success message for creation popup

      // return $this->redirect('admin_post_index');  Route not found: "/admin/admin_post_index/"
      return $this->redirect('/admin/articles');
    }
    return $this->render('admin/post/edit', ['post' => $post]);
  }

  // A externaliser dans un service. PostHandler
  private function validatePostData(string $title, string $content, string $tagline, int $userId): array
  {
    $errors = [];

    if (empty($title)) {
      $errors['title'] = 'Le titre ne peut pas être vide.';
    }
    if (strlen($title) < 3) {
      $errors['title'] = 'Le titre doit faire plus de 3 caractères.';
    }
    if (empty($content)) {
      $errors['content'] = 'Le contenu ne peut pas être vide.';
    }
    if (strlen($content) < 10) {
      $errors['content'] = "Le contenu de l'article doit faire plus de 10 caractères.";
    }
    // if (empty($tagline)) {
    //   $errors['tagline'] = 'La tagline ne peut pas être vide.';
    // }
    // if (empty($userId)) {
    //   $errors['userId'] = "L'identifiant de l'utilisateur ne peut pas être vide.";
    // }

    return $errors;
  }

  public function delete(int $id, string $csrfToken)
  {
    $post = $this->postRepository->findById($id);
    // retrieve comments that are linked to the post
    $comments = $this->commentRepository->getCommentsByPostId($id); // ok on trouve 3 objets commentaires lié au post avec l'id 1

    // check if post method
    if ($this->isSubmitted('deletePost') && $this->isValid($_POST)) {
      //match value hidden with csrfToken in url
      // on rentre bien dans la condition
      if ($_POST['csrfToken'] === $csrfToken) {
        foreach ($comments as $comment) {
          // Use the correct method to get the comment ID
          $this->commentRepository->delete($comment->getId());
        }
        // Delete the post after deleting associated comments
        $this->postRepository->delete($post->getId());
      }
      return $this->redirect('/admin/articles');
    }
    return $this->redirect('/not-found');
  }
}