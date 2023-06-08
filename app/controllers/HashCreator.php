<?php

declare(strict_types=1);

namespace Controllers;

use Libraries\BaseController;

class HashCreator extends BaseController
{
  private $checkedHash;
  private $hashArray;

  public function createArrayHashCodes(array $dataArray): void
  {
    $this->hashArray = array();

    foreach ($dataArray as $item) {
      $hashCode = md5(serialize($item));
      $this->hashArray[$hashCode] = $item;
    }
  }

  public function checkArrayHashCode(string $hashToCheck): bool
  {
    if (isset($this->hashArray[$hashToCheck])) {
      $this->checkedHash = $hashToCheck;
      return true;
    }
    return false;
  }

  public function getHashArrayValue(): mixed
  {
    if (isset($this->hashArray[$this->checkedHash])) {
      return $this->hashArray[$this->checkedHash];
    }
    return null;
  }

  public function getArrayHashKeys(): mixed
  {
    return array_keys($this->hashArray);
  }

  public function getHashArray(): mixed
  {
    return $this->hashArray;
  }

  public function setHashArray($hashArray): void
  {
    $this->hashArray = $hashArray;
  }
}
