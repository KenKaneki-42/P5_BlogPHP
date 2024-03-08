<?php

namespace App\Service\Handler;

use Core\Mailer\MailerConfig;
use PHPMailer\PHPMailer\Exception;
use App\Repository\UserRepository;

class MailerHandler extends MailerConfig
{
  const PATH = 'send-email-contact';
  const CONTACT_EMAIL = 'sylvain.vandermeersch@gmail.com';

  public function __construct()
  {
    parent::__construct();
  }

  public function sendEmailContact(string $senderEmail, string $message): void
  {
    //Create an instance; passing `true` enables exceptions
    try {
      //Recipients
      $this->mail->setFrom(self::CONTACT_EMAIL);
      $this->mail->addAddress(self::CONTACT_EMAIL);

      $body = sprintf("<p>Voici le contenu du message:</br> %s, </br> il provient de %s : </p>", $message, $senderEmail);

      //Content
      $this->mail->isHTML(true);
      $this->mail->Subject = 'Demande de contact';
      $this->mail->Body    = $body;
      $this->mail->AltBody = strip_tags($body);

      $this->mail->send();
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
    }
  }
}
