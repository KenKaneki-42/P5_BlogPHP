<?php

namespace Core\component;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AbstractController
{
  protected $twig;

  public function __construct()
  {
    if (!defined('TEMPLATES_DIR')) {
      throw new \RuntimeException("TEMPLATES_DIR constant is not defined.");
    }
    try {
      $loader = new FilesystemLoader(TEMPLATES_DIR . '/');
      // $loader = new FilesystemLoader(dirname(dirname(__DIR__)) . '/templates');
      $this->twig = new Environment($loader, ["debug" => true]);
    } catch (\Exception $e) {
      throw new \RuntimeException("Error initializing Twig: " . $e->getMessage());
    }
  }

  public function render($template, array $data = [])
  {
    if (!$this->twig) {
      throw new \RuntimeException("Twig environment is not initialized.");
    }

    try {
      // var_dump($template);
      // die;
      return $this->twig->render($template . ".html.twig", $data);
    } catch (LoaderError | RuntimeError | SyntaxError $e) {
      // var_dump('enter catch abstract');
      // die;
      // handle Twig erros
      throw new \RuntimeException("Error rendering template: " . $e->getMessage());
    } catch (\Exception $e) {
      // Gérez d'autres erreurs ici (par exemple, journalisez-la ou lancez une nouvelle exception)
      throw new \RuntimeException("Error rendering template: " . $e->getMessage());
    }
  }

  // TODO redirect 302
  public function redirect(string $url): void
  {
    header('Location:' . $url);
    exit();
  }

  public function isSubmitted($submitButton) : bool
  {
    if (isset($_POST[$submitButton])){
      return true;
    }
    return false;
  }

  public function isValid(array $data) : bool
  {
    $isValid = true;
    foreach ($data as $datum) {
      if(null === $datum  || !isset($datum) || $datum === ''){
        $isValid = false;
      }
    }
    return $isValid;
  }

  //TODO faire passer une clé en session ( success) et faire passer un message en valeur) exemple: 1er tableau clé et 2 eme message
  public function success($key,$message): void
  {
    
  }
}
