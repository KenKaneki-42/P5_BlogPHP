<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Repository\PostRepository;
use Core\database\ConnectionDb; // Import the ConnectionDb class
use Core\Router;

// require_once('../CORE/database.php');
// require_once('../entity/post.php');

class HomepageController extends AbstractController {

  protected PostRepository $postRepository;

  public function __construct()
  {
    parent::__construct();
    $this->postRepository = new PostRepository();
  }

  public function homepage()
  {
    $posts = $this->postRepository->getAll(5);
    // var_dump($posts);
    return $this->render("front/homepage",['posts'=> $posts]);
  }

}
