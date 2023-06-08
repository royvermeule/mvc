<?php

declare(strict_types=1);

namespace Libraries;

class BaseModel
{
  protected Database $db;

  public function __construct($db = new Database)
  {
    $this->db = $db;
  }
}
