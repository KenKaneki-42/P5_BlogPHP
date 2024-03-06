<?php

declare(strict_types=1);

namespace Core\Component;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use App\Entity\User;
use Core\Exception\RedirectException;

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
      $this->twig = new Environment($loader, ["debug" => true]);
    } catch (\Exception $e) {
      throw new \RuntimeException("Error initializing Twig: " . $e->getMessage());
    }
  }

  public function render(string $template, array $params = []): string
  {
    if (!$this->twig) {
      throw new \RuntimeException("Twig environment is not initialized.");
    }

    try {
      $this->twig->addGlobal("session", $_SESSION);
      return $this->twig->render($template . ".html.twig", $params);
    } catch (LoaderError | RuntimeError | SyntaxError $e) {
      throw new \RuntimeException("Error rendering template: " . $e->getMessage());
    } catch (\Exception $e) {
      // Gérez d'autres erreurs ici (par exemple, journalisez-la ou lancez une nouvelle exception)
      throw new \RuntimeException("Error rendering template: " . $e->getMessage());
    }
  }

  protected function redirect(string $url): void
  {
    throw new RedirectException($url);
  }

  public function isSubmitted($submitButton): bool
  {
    if (isset($_POST[$submitButton])) {
      return true;
    }
    return false;
  }

  public function isValid(array $data): bool
  {
    $isValid = true;
    foreach ($data as $key => $value) {
      // Vérifiez si le champ est le champ honeypot
      if ($key === 'honeypot') {
        // S'il est rempli, le formulaire est considéré comme invalide
        if (!empty($value)) {
          $isValid = false;
        }
      } else {
        // Vérifiez les autres champs normalement
        if ($value === null || !isset($value) || $value === '') {
          $isValid = false;
        }
      }
    }
    return $isValid;
  }


  public function isAdmin(User $user = null): bool
  {
    $isAdmin = false;
    if (null !== $user) {
      $Roles = $user->getRole();
      if (in_array('ROLE_ADMIN', $Roles)) {
        $isAdmin = true;
      }
    }
    return $isAdmin;
  }

  public function isAdminPage(): bool
  {
    // Retrieve url of current page
    $currentPageUrl = $_SERVER['REQUEST_URI'];

    // check if URL starts with '/admin/'
    return strpos($currentPageUrl, "/admin/") === 0;
  }

  public function checkAdminAccess(User $user = null): void
  {
    // if ($this->isAdminPage() && !$this->isAdmin($user)) {
    //   // Redirige l'utilisateur vers une page d'erreur ou d'accueil
    //   $this->redirect('/forbidden');
    // }
    if ($this->isAdminPage() && !$this->isAdmin($user)) {
      throw new \Core\Exception\ForbiddenAccessException("Accès interdit.");
    }
  }
  public function validateCaptcha($captchaResponse): bool
  {
    $secretKeyCaptcha = $_ENV['RECAPTCHA_SECRET_KEY'];
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKeyCaptcha&response=$captchaResponse");
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) === 1) {
      return true;
    } else {
      return false;
    }
  }

  public function addMessageFlash(string $key, string $message): void
  {
    $_SESSION[$key] = $message;
  }
}
