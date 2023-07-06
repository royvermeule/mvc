<?php

declare(strict_types=1);

namespace Libraries;

use Controllers\Errors\NotFound;
use Utility\PageLogger;
use Utility\Session;

class Core
{
  protected mixed $currentController = 'Home';
  protected mixed $currentMethod = 'index';
  protected array $params = [];
  protected array $params2 = [];

  public function __construct()
  {
    $controllerNotFound = false;
    $methodNotFound = false;

    $url = $this->getUrl();

    if ($url[0] !== 'pages') {
      if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
        $this->currentController = ucwords($url[0]);
        unset($url[0]);
      } else {
        $controllerNotFound = true;
      }
    }

    require_once '../app/controllers/' . $this->currentController . '.php';

    $controller = 'Controllers\\' . $this->currentController . '';

    $this->currentController = new $controller;

    if (!$controllerNotFound) {
      if (isset($url[1])) {
        // Check to see if method exists in controller
        if (method_exists($this->currentController, $url[1])) {
          $this->currentMethod = $url[1];

          unset($url[1]);
        } else {
          $methodNotFound = true;
        }
      } else {
        $methodNotFound = true;
      }
    }

    if ($controllerNotFound || $methodNotFound) {
      $notFound = new NotFound();
      $notFound->index();
    }

    if (!$controllerNotFound && !$methodNotFound) {
      Session::startSession();

      if ($this->checkCurrentPage()) {
        $this->storeLastPage();
      }

      $_SESSION['currentPage'] = [$this->currentController->__toString(), $this->currentMethod];
      $this->params = $url ? array_values($url) : [];
      $this->params2 = []; // Initialize the second parameter as an empty array

      call_user_func_array([$this->currentController, $this->currentMethod], array_merge($this->params, $this->params2));
    }
  }

  /**
   * @return string[]
   */
  public function getUrl(): array
  {
    if (isset($_GET['url'])) {
      $url = rtrim($_GET['url'], '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', $url);
      return $url;
    } else {
      $url = ['pages', 'index'];
      return $url;
    }
  }

  protected function storeLastPage(): void
  {
    $currentPage = $_SESSION['currentPage'];

    if (!isset($_SESSION['lastPage'])) {
      // Set the lastPage initially
      $_SESSION['lastPage'] = $currentPage;
    } else {
      // Update lastPage if necessary
      $lastPage = $_SESSION['lastPage'];
      if ($lastPage[0] !== $currentPage[0] || $lastPage[1] !== $currentPage[1]) {
        $_SESSION['lastPage'] = $currentPage;
      }
    }
  }

  private function checkCurrentPage(): bool
  {
    $currentPage = $_SESSION['currentPage'];
    if ($currentPage[0] === $this->currentController->__toString() && $currentPage[1] === $this->currentMethod) {
      return false;
    }
    return true;
  }
}
