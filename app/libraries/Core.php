<?php

declare(strict_types=1);

namespace Libraries;

class Core
{
  protected $currentController = 'Home';
  protected $currentMethod = 'index';
  protected $params = [];
  protected $params2 = [];

  public function __construct()
  {

    $url = $this->getUrl();
    // var_dump($url);exit();

    if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {

      $this->currentController = ucwords($url[0]);

      unset($url[0]);
    }

    require_once '../app/controllers/' . $this->currentController . '.php';

    $controller = 'Controllers\\' . $this->currentController . '';

    $this->currentController = new $controller;


    if (isset($url[1])) {
      // Check to see if method exists in controller
      if (method_exists($this->currentController, $url[1])) {
        $this->currentMethod = $url[1];
        // Unset 1 index
        unset($url[1]);
      }
    }

    // Get params
    $this->params = $url ? array_values($url) : [];
    $this->params2 = []; // Initialize the second parameter as an empty array

    // Call a callback with array of params
    call_user_func_array([$this->currentController, $this->currentMethod], array_merge($this->params, $this->params2));
  }

  public function getUrl()
  {
    if (isset($_GET['url'])) {
      $url = rtrim($_GET['url'], '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', $url);
      return $url;
    } else {
      $url = array('pages', 'index');
      return $url;
    }
  }
}
