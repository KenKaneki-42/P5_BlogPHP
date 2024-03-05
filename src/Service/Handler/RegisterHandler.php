<?php

namespace App\Service\Handler;

use Core\Mailer\MailerConfig;
use PHPMailer\PHPMailer\Exception;
use App\Repository\UserRepository;

class RegisterHandler extends MailerConfig
{
  const PATH_CONFIRMATION_INSCRIPTION = 'inscription-confirmation';
  const PATH_RESET_PASSWORD = 'mot-de-passe-oublie';

  public function __construct()
  {
    parent::__construct();
  }

  public function sendEmailConfirmation(string $recipient, string $token)
  {
    $confirmationUrl = $this->buildUrl(self::PATH_CONFIRMATION_INSCRIPTION, $token);
    $body = sprintf("<p>Bonjour, pour confirmer votre inscription, cliquez sur le lien : <strong>%s</strong></p>", $confirmationUrl);

    $this->setupAndSendEmail('Confirmation de demande de création de compte', $body, $recipient);
  }

  public function sendEmailResetPassword(string $recipient, string $token)
  {
    $url = $this->getRequestUrl(self::PATH_RESET_PASSWORD) . $token;
    $body = sprintf("<p>Bonjour, afin de confirmer la réinitialisation du mot de passe, merci de cliquer sur le lien suivant: <strong> %s </strong></p>", $url);

    $this->setupAndSendEmail('Demande de réinitialisation de mot de passe', $body, $recipient);
  }

  public function sendEmailConfirmChangementPassword(string $recipient): void
  {
    $body = "<p>Bonjour, votre mot de passe à bien été modifié</p>";

    $this->setupAndSendEmail('Confirmation de réinitialisation de mot de passe', $body, $recipient);
  }

  public function getRequestUrl(string $path): string
  {
    // Vérifiez si HTTPS est utilisé, sinon utilisez HTTP
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    // Récupérez le nom de l'hôte à partir de la variable SERVER
    $hostName = $_SERVER['HTTP_HOST'];

    // Construisez l'URL de base
    $baseUrl = $protocol . $hostName;

    // Affichez l'URL de base
    return $baseUrl . "/" . $path . "/";
  }
}
