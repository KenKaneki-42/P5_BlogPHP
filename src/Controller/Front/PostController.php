<?php

namespace App\Controller\Front;

use Core\component\AbstractController;
use App\Repository\PostRepository;
use Core\database\ConnectionDb; // Import the ConnectionDb class
use Core\Router;

class PostController extends AbstractController {

  protected PostRepository $postRepository;

  public function __construct()
  {
    parent::__construct();
    $this->postRepository = new PostRepository();
  }

  public function index()
  {
    $posts = $this->postRepository->getAll(100);
    return $this->render("front/posts",['posts'=> $posts]);
  }
}
