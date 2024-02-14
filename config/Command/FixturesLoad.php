<?php

namespace Core\Command;

use App\Fixtures\CommentFixtures;
use App\Fixtures\PostFixtures;
use App\Fixtures\UserFixtures;
use Core\Component\ConsoleIO;
use Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

$consoleIO = new ConsoleIO();
// Instantiate and execute the Fixtures classes in this order: User, Post, Comment.

for ($i = 0; $i < 10; $i++) {
  try {
    $consoleIO->writeLine('Starting to load a new user');
    $userFixtures = new UserFixtures();
    $userIdGenerated = $userFixtures->load();
    $consoleIO->writeLine(sprintf('New user generated and loaded in database with id: %s', $userIdGenerated));
  } catch (Exception $e) {
    echo "Error during the execution of UserFixtures : " . $e->getMessage() . "\n";
  }
}

for ($i = 0; $i < 15; $i++) {
  try {
    $consoleIO->writeLine('Starting to load  a new post');
    $postFixtures = new PostFixtures();
    $postIdGenerated = $postFixtures->load();
    $consoleIO->writeLine(sprintf('New post generated and loaded in database with id: %s', $postIdGenerated));
  } catch (Exception $e) {
    echo "Error during the execution of PostFixtures" . $e->getMessage() . "\n";
  }
}

for ($i = 0; $i < 15; $i++) {
  try {
    $consoleIO->writeLine('Starting to load a new comment');
      $commentFixtures = new CommentFixtures();
      $commentIdGenerated = $commentFixtures->load();
      $consoleIO->writeLine(sprintf('New comment generated and loaded in database with id: %s', $commentIdGenerated));
  } catch (Exception $e) {
      echo "Error during the execution CommentFixtures : " . $e->getMessage() . "\n";
  }
}

echo " Execution of fixtures finished.\n";
