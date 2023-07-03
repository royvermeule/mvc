<?php

declare(strict_types=1);

namespace Models;

use Libraries\BaseModel;

class HomeModel extends BaseModel
{
  /**
   * @return string
   */
  public function index(): string
  {
    return 'this is coming from the model';
  }
}
