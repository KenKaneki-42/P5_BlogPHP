<?php

namespace App\Controller;

use Core\Component\AbstractController;

class LegalInfoController extends AbstractController
{
  public function __construct()
  {
    parent::__construct();
  }

  public function showLegalInfoPage() : string
  {
    return $this->render('front/legalInformations');
  }
}
