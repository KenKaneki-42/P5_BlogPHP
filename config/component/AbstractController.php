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
      $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    } catch (\Exception $e) {
      throw new \RuntimeException("Error initializing Twig: " . $e->getMessage());
    }
  }

  public function render(string $template, array $params = []): string
  {
    if (!$this->twig) {
      throw new \RuntimeException("Twig environment is not initialized.");
    }

    $this->twig->addGlobal("session", $_SESSION);
    if (isset($_SESSION['flashMessages'])) {
      $this->twig->addGlobal("flashMessages", $_SESSION['flashMessages']);
    }

    try {
      $content = $this->twig->render($template . ".html.twig", $params);

      if (isset($_SESSION['flashMessages'])) {
        unset($_SESSION['flashMessages']);
      }

      return $content;
    } catch (LoaderError | RuntimeError | SyntaxError $e) {
      throw new \RuntimeException("Error rendering template: " . $e->getMessage());
    } catch (\Exception $e) {
      throw new \RuntimeException("Error rendering template: " . $e->getMessage());
    }
  }

  protected function redirect(string $url): string
  {
    header("Location: " . $url);
    exit();
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
    if ($this->isAdminPage() && !$this->isAdmin($user)) {
      $this->redirect('/forbidden');
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

  public function addMessageFlash(string $type, string $message): void
  {
    if (!isset($_SESSION['flashMessages'][$type])) {
      $_SESSION['flashMessages'][$type] = [];
    }
    $_SESSION['flashMessages'][$type][] = $message;
  }
}
