<?php

namespace App\Controller;

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
    }

    // public function index(): void
    // {
    //     $posts = $this->postRepository->getAll(10);
    //     $this->render('home/index.html.twig', ['posts' => $posts]);
    // }

    public function index()
    {
        $posts = $this->postRepository->getAll(20); // Récupérez vos posts ici depuis la base de données
        return $this->render('admin/post/index', ['posts' => $posts]);
    }

    public function show(int $id): void
    {
        $post = $this->postRepository->findById($id);
        // fetch all comments link to the previous
        // $comments = $this->getComments($id);

        // $this->render('post/show.html.twig', ['post' => $post, 'comments' => $comments]);
    }

    public function update(string $id, Post $post): void
    {
      return;
    }


    public function delete(int $id): void
    {

    }

}
