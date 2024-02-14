<?php

namespace Core\Command;

use App\Repository\UserRepository;
use Core\component\ConsoleIO;
use Exception;

try{
  $consoleIO = new ConsoleIO();
  $consoleIO->writeLine("Please write the email of the user you want to add role as ROLE_ADMIN");
  $email = $consoleIO->readLine("email");
  $userRepository = new UserRepository();
  $user = $userRepository->findByEmail($email);
  if ($user === null) {
    $consoleIO->writeLine("User with this email doesn't exist in database");
  } else {
    $user->setAsAdmin($user);
    $userRepository->save($user);
    $consoleIO->writeLine("User with email ". $email. " has been added as admin");
  }
}catch(Exception $e){
  echo "Error during the execution UserFixtures : " . $e->getMessage() . "\n";
}
