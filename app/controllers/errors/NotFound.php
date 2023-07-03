<?php

declare(strict_types=1);

namespace Controllers\Errors;

use Libraries\BaseController;

class NotFound extends BaseController
{
  public function index(): void
  {
    $this->view('errors/404');
  }
}
