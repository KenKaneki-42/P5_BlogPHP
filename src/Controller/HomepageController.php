<?php

namespace App\Controller;

use Core\Component\AbstractController;
use App\Repository\PostRepository;

class HomepageController extends AbstractController
{

  protected PostRepository $postRepository;

  public function __construct()
  {
    parent::__construct();
    $this->postRepository = new PostRepository();
  }

  public function homepage(): ?string
  {
    return $this->render('front/homepage', ['recaptchaPublicKey' => $_ENV['RECAPTCHA_PUBLIC_KEY']]);
  }
}
