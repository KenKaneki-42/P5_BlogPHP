<?php

namespace Core\Mailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerConfig
{
  const PROTOCOLE = 'http';
  const HOST = '127.0.0.1';
  const PORT = '8000';
  const SENDER = 'sylvain.vandermeersch@gmail.com';
  protected $mail;

  public function __construct()
  {
    $this->mail = new PHPMailer(true);

    //Server settings
    $this->mail->isSMTP();                                            //Send using SMTP
    $this->mail->Host       = $_ENV['MAILER_HOST'];                     //Set the SMTP server to send through
    $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $this->mail->Username   = $_ENV['MAILER_USERNAME'];                     //SMTP username
    $this->mail->Password   = $_ENV['MAILER_PASSWORD'];                               //SMTP password
    $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $this->mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $this->mail->CharSet = 'UTF-8';
  }
  protected function buildUrl(string $path, string $token = ''): string
  {
    $url = sprintf('%s://%s:%s/%s', self::PROTOCOLE, self::HOST, self::PORT, $path);
    if (!empty($token)) {
      $url .= '/' . $token;
    }
    return $url;
  }

  protected function setupAndSendEmail($subject, $body, $recipient, $altBody = '')
  {
    try {
      //Recipients
      $this->mail->setFrom(self::SENDER, 'SVDM');
      $this->mail->addAddress($recipient);
      // Vérifiez si HTTPS est utilisé, sinon utilisez HTTP
      //Content
      $this->mail->isHTML(true);
      $this->mail->Subject = $subject;
      $this->mail->Body    = $body;
      $this->mail->AltBody = $altBody ? $altBody : strip_tags($body);

      $this->mail->send();
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
    }
  }
}
