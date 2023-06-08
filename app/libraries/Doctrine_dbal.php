<?php

declare(strict_types=1);

namespace Libraries;

use Doctrine\DBAL\DriverManager;

class Doctrine_dbal
{
  public function connection()
  {
    $connectionParams = [
      'dbname' => DB_NAME,
      'user' => DB_USER,
      'password' => DB_PASS,
      'host' => DB_HOST,
      'driver' => 'pdo_mysql',
    ];

    $conn = DriverManager::getConnection($connectionParams);

    $stmt = $conn->prepare('SELECT * FROM user WHERE user_id = ?');

    $result = $stmt->executeQuery([1]);

    var_dump($result->fetchAllAssociative());
  }
}
