<?php

declare(strict_types=1);

namespace Utility;

class ErrorsStorer
{
  /**
   * @param array $errors
   * @param bool $auth
   * @return void
   */
  public static function storeErrors(array $errors, bool $auth = false): void
  {
    $encrypter = new Encrypter();
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    if (!isset($_SESSION['errors'])) {
      $encryptedErrors = $encrypter->encryptArray($errors);
      $_SESSION['errors'] = $encryptedErrors;
    } else {
      $decryptedErrors = $encrypter->decryptArray($_SESSION['errors']);
      $_SESSION['errors'] = '';
      $errors = array_merge($decryptedErrors, $errors);
      $encryptedErrors = $encrypter->encryptArray($errors);
      $_SESSION['errors'] = $encryptedErrors;
    }
    if (!$auth) {
      session_destroy();
    }
  }

  /**
   * @param bool $auth
   * @return array
   */
  public static function getErrors(bool $auth): array
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $encrypter = new Encrypter();
    $decryptedErrors = $encrypter->decryptArray($_SESSION['errors']);
    if (!$auth) {
      session_destroy();
    }

    return $decryptedErrors;
  }
}
