<?php

declare(strict_types=1);

namespace Utility;

class PageLogger
{
  /**
   * @param mixed $controller
   * @param mixed $method
   * @return void
   */
  public static function logCurrentPage(mixed $controller, mixed $method): void
  {
    if (session_status() === 1) {
      session_start();
    }
    $controller = basename($controller);
    $currentPage = array($controller, $method);
    $encrypter = new Encrypter();
    $encryptedPage = $encrypter->encryptArray($currentPage, '939dj9jdkj:KJ:D93kdjdj');
    if (isset($_SESSION['currentPage'])) {
      $_SESSION['currentPage'] = '';
    }
    self::logLastPage($encryptedPage);
    $_SESSION['currentPage'] = $encryptedPage;
  }

  /**
   * @param string $encryptedPage
   * @return void
   */
  public static function logLastPage(string $encryptedPage): void
  {
    if (session_status() === 1) {
      session_start();
    }
    $_SESSION['lastPage'] = '';
    $_SESSION['lastPage'] = $encryptedPage;
  }

  /**
   * @return array
   */
  public static function getCurrentPage(): array
  {
    if (session_status() === 1) {
      session_start();
    }
    $encrypter = new Encrypter();
    return $encrypter->decryptArray($_SESSION['currentPage'], '939dj9jdkj:KJ:D93kdjdj');
  }

  /**
   * @return array
   */
  public static function getLastPage(): array
  {
    if (session_status() === 1) {
      session_start();
    }
    $encrypter = new Encrypter();
    return $encrypter->decryptArray($_SESSION['lastPage'], '939dj9jdkj:KJ:D93kdjdj');
  }

  /**
   * @param mixed $controller
   * @param mixed $method
   * @return bool
   */
  public static function checkCurrentPage(mixed $controller, mixed $method): bool
  {
    if (session_status() === 1) {
      session_start();
    }
    if (isset($_SESSION['currentPage'])) {
      $encrypter = new Encrypter();
      $currentPage = $encrypter->decryptArray($_SESSION['currentPage'], '939dj9jdkj:KJ:D93kdjdj');
      if ($currentPage[0] === $controller && $currentPage[1] === $method) {
        return false;
      }
    }
    return true;
  }
}
