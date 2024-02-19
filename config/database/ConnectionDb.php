<?php
// pdo, call in differents manangers to execute SQL queries
namespace Core\database;

use PDO;

class ConnectionDb
{
  private static $database;

  public function __construct()
  {
    // eviter une instanciation de classe, souhaiter juste utiliser la méthode static
  }
  public static function getConnection(): PDO
  {
    if (self::$database === null) {
      self::$database = new PDO('mysql:host=localhost;dbname=OC_P5_DB_blog;charset=utf8', 'root', 'Abcdef+200594');
    }

    return self::$database;
  }
}
