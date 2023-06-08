<?php

declare(strict_types=1);

namespace Controllers;

use Controllers\HashCreator;
use Libraries\BaseController;
use Models\HomepageModel;

class Home extends BaseController
{
  private $homeModel;

  public function __construct()
  {
    $this->homeModel = new HomepageModel();
  }

  public function index(): void
  {
    $data = [
      'title' => 'Home'
    ];

    $this->view('homepages/index', $data);
  }
}
