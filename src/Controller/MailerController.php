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

  public function sendContentContactForm(): string
  {
    if ($this->isSubmitted('submitContactForm')) {
      $errors = [];
      if (!isset($_POST["checkbox-legal-infos"])) {
        $errors["checkbox-legal-infos"] = "Vous devez accepter les informations légales";
      }
      // Validation form
      if (!$this->isValid($_POST)) {
        $errors[] = "Tous les champs du formulaire sont obligatoires.";
      }

      // Validation captcha
      if (!$this->validateCaptcha($_POST['g-recaptcha-response'])) {
        $errors[] = "Le captcha n'a pas été validé.";
      }

      // no error send email
      if (empty($errors)) {
        $senderEmail = $_POST['email'];
        $message = $_POST['message'];

        $this->mailerHandler->sendEmailContact($senderEmail, $message);
        $this->addMessageFlash('success', 'Votre email a bien été envoyé');
        return $this->redirect("/homepage?emailSent=true");
      }

      return $this->render("front/errors", ['errors' => $errors]);
    }

    return $this->redirect("/homepage");
  }
}
