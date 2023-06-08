<?php

declare(strict_types=1);

namespace Libraries;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;

/**
 * @mixin Connection
 */
class Database
{
  private Connection $connection;

  public function __construct()
  {
    $connectionParams = [
      'dbname' => DB_NAME,
      'user' => DB_USER,
      'password' => DB_PASS,
      'host' => DB_HOST,
      'driver' => 'pdo_mysql',
    ];

    $this->connection = DriverManager::getConnection($connectionParams);
  }

  public function __call($method, $arguments)
  {
    return call_user_func_array([$this->connection, $method], $arguments);
  }
}
