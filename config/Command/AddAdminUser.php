<?php

namespace Core\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Core\component\ConsoleIO;
use Exception;
// pourquoi je dois utiliser l'autoload.php ici pour que le namespace app soit présent dans le namespace core?
// require_once __DIR__ . '/../../vendor/autoload.php';

try{
  $consoleIO = new ConsoleIO();
  $consoleIO->writeLine("please write the email of the user you want to add role as ROLE_ADMIN");
  $email = $consoleIO->readLine("email");
  $userRepository = new UserRepository();
  $user = $userRepository->findByEmail($email);
  if ($user === null) {
    $consoleIO->writeLine("user with this emaildon't exist in database");
  } else {
    $user->setAsAdmin($user);
    $userRepository->save($user);
    $consoleIO->writeLine("user with email ". $email. " has been added as admin");
  }
}catch(Exception $e){
  echo "Erreur lors de l'exécution de UserFixtures : " . $e->getMessage() . "\n";
}
