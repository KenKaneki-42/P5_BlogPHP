<?php

namespace App\Controller;

use Core\Component\AbstractController;
use App\Service\Handler\MailerHandler;

class MailerController extends AbstractController
{

  private MailerHandler $mailerHandler;

  public function __construct()
  {
    parent::__construct();
    $this->mailerHandler = new MailerHandler;
  }

  public function sendContentContactForm()
  {

    if ($this->isSubmitted('submitContactForm') && $this->isValid($_POST) && $this->validateCaptcha($_POST['g-recaptcha-response'])) {
      $senderEmail = $_POST['email'];
      $message = $_POST['message'];
      $firstname = $_POST['firstname'];
      $lastname = $_POST['lastname'];
      // $validationErrors = $this->validateContactFormData($senderEmail, $firstname, $lastname, $message);

      // if(!empty($validationErrors)){
      $this->mailerHandler->sendEmailContact($senderEmail, $message);
      // }
      return $this->redirect("/homepage?emailSent=true");
    }
    // erreur
    // email envoyé confirmation
  }
}
