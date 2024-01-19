<?php

namespace Core\Command;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Fixtures\CommentFixtures;
use App\Fixtures\PostFixtures;
use App\Fixtures\UserFixtures;
use Core\component\ConsoleIO;
use Exception;

$consoleIO = new ConsoleIO();
// Instantiate and execute the Fixtures classes in this order: User, Post, Comment.

for ($i = 0; $i < 10; $i++) {
  try {
    $consoleIO->writeLine('start load a new user');
    $userFixtures = new UserFixtures();
    $userIdGenerated = $userFixtures->load();
    $consoleIO->writeLine(sprintf('new user generated and loaded in database with id: %s', $userIdGenerated));
  } catch (Exception $e) {
    echo "Erreur lors de l'exécution de UserFixtures : " . $e->getMessage() . "\n";
  }
}

for ($i = 0; $i < 15; $i++) {
  try {
    $consoleIO->writeLine('start loading new post');
    $postFixtures = new PostFixtures();
    $postIdGenerated = $postFixtures->load();
    $consoleIO->writeLine(sprintf('new post generated and loaded in database with id: %s', $postIdGenerated));
  } catch (Exception $e) {
    echo "Error during the execution of PostFixtures" . $e->getMessage() . "\n";
  }
}

for ($i = 0; $i < 15; $i++) {
  try {
    $consoleIO->writeLine('start load new comment');
      $commentFixtures = new CommentFixtures();
      $commentIdGenerated = $commentFixtures->load();
      $consoleIO->writeLine(sprintf('new comment generated and loaded in database with id: %s', $commentIdGenerated));
  } catch (Exception $e) {
      echo "Erreur lors de l'exécution de CommentFixtures : " . $e->getMessage() . "\n";
  }
}

echo " Exécution des Fixtures terminée.\n";

// try to add a audio file for winning
