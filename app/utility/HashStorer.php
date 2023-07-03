<?php

declare(strict_types=1);

namespace Utility;

use Libraries\BaseController;

class HashStorer extends BaseController
{
    /**
     * @param array $hashArray
     * @param $encreptionKey
     * @return void
     */
  public static function setHashArray(array $hashArray, $encreptionKey): void
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $encrypter = new Encrypter();
    $encryptedHashArray = $encrypter->encryptArray($hashArray, $encreptionKey);
    self::emtyHashArrayInSession();
    $_SESSION['hashArray'] = $encryptedHashArray;
  }

  public static function getStoredHashArray($encreptionKey)
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $encrypter = new Encrypter();
    $decryptedHashArray = $encrypter->decryptArray($_SESSION['hashArray'], $encreptionKey);

    return $decryptedHashArray;
  }

  public static function emtyHashArrayInSession(): void
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $_SESSION['hashArray'] = '';
  }
}
