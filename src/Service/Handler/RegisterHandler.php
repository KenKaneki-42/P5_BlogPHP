<?php

namespace App\Service\Handler;

use Core\Mailer\MailerConfig;
use PHPMailer\PHPMailer\Exception;
use App\Repository\UserRepository;

class RegisterHandler extends MailerConfig
{
  const PROTOCOLE = 'http';
  const HOST = '127.0.0.1';
  const PORT = '8000';
  const PATH_CONFIRMATION_INSCRIPTION = 'inscription-confirmation';
  const PATH_RESET_PASSWORD = 'mot-de-passe-oublie';
  const SENDER = 'sylvain.vandermeersch@gmail.com';

  public function __construct()
  {
    parent::__construct();
  }

  public function validateUserData(string $lastName, string $firstName, string $password, string $confirmedPassword, string $email)
  {
    $errors = [];

    if (!isset($lastName) || empty($lastName)) {
      $errors['lastName'] = 'Le nom ne peut pas être vide.';
    }
    if (!isset($firstName) || empty($firstName)) {
      $errors['firstName'] = 'Le prénom ne peut pas être vide.';
    }
    if (!isset($password) || empty($password)) {
      $errors['password'] = 'Le mot de passe ne peut pas être vide';
    }
    if (!isset($confirmedPassword) || empty($confirmedPassword)) {
      $errors['confirmedPassword'] = 'La confirmation du mot de passe ne peut pas être vide';
    }
    if ($password !== $confirmedPassword) {
      $errors['matching password'] = 'les mots de passes ne correspondent pas';
    }

    if (empty($email)) {
      $errors['email'] = 'L\'adresse email ne peut pas être vide.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'L\'adresse email n\'est pas valide.';
    }

    $userRepository = new UserRepository;
    if ($userRepository->emailExists($email) > 0) {
      $errors['email'] = "L'email existe déjà en base de donnée";
    }

    //check if the email already exist in database

    return $errors;
  }

  public function sendEmailConfirmation(string $recipient, string $token)
  {
    //Create an instance; passing `true` enables exceptions

    try {
      //Recipients
      $this->mail->setFrom('sylvain.vandermeersch@gmail.com', 'SVDM');
      $this->mail->addAddress($recipient);     //Add a recipient

      // $this->mail->addAddress('ellen@example.com');               //Name is optional
      // $this->mail->addReplyTo('info@example.com', 'Information');
      // $this->mail->addCC('cc@example.com');
      // $this->mail->addBCC('bcc@example.com');

      //Attachments
      // $this->mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
      // $this->mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
      $confirmationUrl = sprintf('%s://%s:%s/%s/%s', self::PROTOCOLE, self::HOST, self::PORT, self::PATH_CONFIRMATION_INSCRIPTION, $token);
      $body = sprintf("<p>Bonjour, afin de confirmer votre inscription, merci de cliquer sur le lien suivant: <strong> %s </strong></p>", $confirmationUrl);
      //Content
      $this->mail->isHTML(true);                                  //Set email format to HTML
      $this->mail->Subject = 'Confirmation de demande de création de compte';
      $this->mail->Body    = $body;
      $this->mail->AltBody = strip_tags($body);

      $this->mail->send();
      //TODO historiser l'envoie du mail?
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
    }
  }

  public function sendEmailResetPassword(string $recipient, string $token)
  {
    try {
      $this->mail->setFrom(self::SENDER, 'SVDM');
      $this->mail->addAddress($recipient);
      $confirmationUrl = sprintf('%s://%s:%s/%s/%s', self::PROTOCOLE, self::HOST, self::PORT, self::PATH_RESET_PASSWORD, $token);
      $body = sprintf("<p>Bonjour, afin de confirmer la reinitialisation du mot de passe, merci de cliquer sur le lien suivant: <strong> %s </strong></p>", $confirmationUrl);
      //Content
      $this->mail->isHTML(true);
      $this->mail->Subject = 'Demande de reinitialisation de mot de passe';
      $this->mail->Body    = $body;
      $this->mail->AltBody = strip_tags($body);

      $this->mail->send();
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
    }
  }

  public function sendEmailConfirmChangementPassword(string $recipient): void
  {
    try {
      $this->mail->setFrom(self::SENDER, 'SVDM');
      $this->mail->addAddress($recipient);
      $body = sprintf("<p>Bonjour, votre mot de passe à bien été modifé</p>");
      //Content
      $this->mail->isHTML(true);
      $this->mail->Subject = 'Confirmation de reinitialisation de mot de passe';
      $this->mail->Body    = $body;
      $this->mail->AltBody = strip_tags($body);

      $this->mail->send();
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
    }
  }
}
