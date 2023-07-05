<?php

declare(strict_types=1);

namespace Controllers;

use Libraries\BaseController;
use Models\HomeModel;

class Home extends BaseController
{
  private HomeModel $homeModel;

  public function __construct()
  {
    $this->homeModel = new HomeModel();
  }


  public function index(): void
  {
    $data = [
      'title' => 'Home',
      'modelMessage' => $this->homeModel->index()
    ];

    $this->view('homepages/index', $data);
  }

  public function test()
  {}


  public function __toString() {
    return 'Home';
  }
}
