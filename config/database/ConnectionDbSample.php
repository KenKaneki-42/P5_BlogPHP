<?php

namespace Core\database;

use PDO;

class ConnectionDbSample
{
  private static $database;

  public function __construct()
  {
  }
  public static function getConnection(): PDO
  {
    if (self::$database === null) {
      self::$database = new PDO('mysql:host=hostname;dbname=dbname;charset=utf8', 'username', 'password');
    }

    return self::$database;
  }
}
