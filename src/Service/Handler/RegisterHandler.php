<?php

namespace App\Service\Handler;

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use Core\Mailer\MailerConfig;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Repository\UserRepository;

class RegisterHandler extends MailerConfig
{
  const PROTOCOLE = 'http';
  const HOST = '127.0.0.1';
  const PORT = '8000';
  const PATH = 'inscription-confirmation';

  public function __construct(){
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
    if ($password !== $confirmedPassword){
      $errors['matching password'] = 'les mots de passes ne correspondent pas';
    }

    if (empty($email)) {
      $errors['email'] = 'L\'adresse email ne peut pas être vide.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'L\'adresse email n\'est pas valide.';
    }

    $userRepository = new UserRepository;
    if ($userRepository->emailExists($email) >0){
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
      $this->mail->setFrom('sylvain.vandermeersch@gmail.com', 'Dono');
      $this->mail->addAddress($recipient);     //Add a recipient

      // $this->mail->addAddress('ellen@example.com');               //Name is optional
      // $this->mail->addReplyTo('info@example.com', 'Information');
      // $this->mail->addCC('cc@example.com');
      // $this->mail->addBCC('bcc@example.com');

      //Attachments
      // $this->mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
      // $this->mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
      $confirmationUrl = sprintf('%s://%s:%s/%s/%s',self::PROTOCOLE,self::HOST, self::PORT, self::PATH,$token);
      $body = sprintf("<p>Bonjour, afin de confirmer votre inscription, merci de cliquer sur le lien suivant: <strong> %s </strong></p>", $confirmationUrl);
      http://127.0.0.1/
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

  // public function activeAccount($email){
  //   // find user by email and request to update the status of the user
  //   $userRepository = new UserRepository();
  //   $user = $userRepository->findByEmail($email);


  // }
}
