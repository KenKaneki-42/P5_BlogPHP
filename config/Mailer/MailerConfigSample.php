<?php

namespace Core\Mailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerConfigSample {
  const PROTOCOLE = 'http or https';
  const HOST = 'host';
  const PORT = 'port';
  const SENDER = 'sender email address';
  protected $mail;

  public function __construct(){
    $this->mail = new PHPMailer(true);

    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $this->mail->isSMTP();                                            //Send using SMTP
    $this->mail->Host       = 'host';                     //Set the SMTP server to send through
    $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $this->mail->Username   = 'username';                     //SMTP username
    $this->mail->Password   = 'password';                               //SMTP password
    $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $this->mail->Port       = 'port';
    $this->mail->CharSet = 'UTF-8';                                //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

  }

  protected function setupAndSendEmail($subject, $body, $recipient, $altBody = '')
  {
    try {
      //Recipients
      $this->mail->setFrom(self::SENDER, 'name');
      $this->mail->addAddress($recipient);
      // $this->mail->addAddress('ellen@example.com');               //Name is optional
      // $this->mail->addReplyTo('info@example.com', 'Information');
      // $this->mail->addCC('cc@example.com');
      // $this->mail->addBCC('bcc@example.com');

      //Attachments
      // $this->mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
      // $this->mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
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
