<?php

namespace App\Service\Handler;

use Core\Mailer\MailerConfig;
use PHPMailer\PHPMailer\Exception;
use App\Repository\UserRepository;

class MailerHandler extends MailerConfig
{
  // const PROTOCOLE = 'http';
  // const HOST = '127.0.0.1';
  // const PORT = '8000';
  const PATH = 'send-email-contact';
  const CONTACT_EMAIL = 'sylvain.vandermeersch@gmail.com';

  public function __construct()
  {
    parent::__construct();
  }

  public function sendEmailContact(string $senderEmail, string $message)
  {
    //Create an instance; passing `true` enables exceptions

    try {
      //Recipients
      $this->mail->setFrom(self::CONTACT_EMAIL);
      $this->mail->addAddress(self::CONTACT_EMAIL);     //Add a recipient

      // $this->mail->addAddress('ellen@example.com');               //Name is optional
      // $this->mail->addReplyTo('info@example.com', 'Information');
      // $this->mail->addCC('cc@example.com');
      // $this->mail->addBCC('bcc@example.com');

      //Attachments
      // $this->mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
      // $this->mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

      // $confirmationUrl = sprintf('%s://%s:%s/%s', self::PROTOCOLE, self::HOST, self::PORT, self::PATH);
      $confirmationUrl = $this->buildUrl(self::PATH);
      $body = sprintf("<p>Voici le contenu du message:</br> %s, </br> il provient de %s : </p>", $message, $senderEmail);
      http: //127.0.0.1/
      //Content
      $this->mail->isHTML(true);                                  //Set email format to HTML
      $this->mail->Subject = 'Demande de contact';
      $this->mail->Body    = $body;
      $this->mail->AltBody = strip_tags($body);

      $this->mail->send();
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
    }
  }
}
