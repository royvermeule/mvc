<?php

declare(strict_types=1);

namespace Controllers;

use Libraries\BaseController;

class HashCreator extends BaseController
{
  private string $checkedHash;
  private array $hashArray;

    /**
     * @param array $dataArray
     * @return void
     */
  public function createArrayHashCodes(array $dataArray): void
  {
    $this->hashArray = array();

    foreach ($dataArray as $item) {
      $hashCode = md5(serialize($item));
      $this->hashArray[$hashCode] = $item;
    }
  }

    /**
     * @param string $hashToCheck
     * @return bool
     */
  public function checkArrayHashCode(string $hashToCheck): bool
  {
    if (isset($this->hashArray[$hashToCheck])) {
      $this->checkedHash = $hashToCheck;
      return true;
    }
    return false;
  }

    /**
     * @return mixed
     */
  public function getHashArrayValue(): mixed
  {
    if (isset($this->hashArray[$this->checkedHash])) {
      return $this->hashArray[$this->checkedHash];
    }
    return null;
  }

    /**
     * @return mixed
     */
  public function getArrayHashKeys(): array
  {
    return array_keys($this->hashArray);
  }

    /**
     * @return array
     */
  public function getHashArray(): array
  {
    return $this->hashArray;
  }

    /**
     * @param $hashArray
     * @return void
     */
  public function setHashArray($hashArray): void
  {
    $this->hashArray = $hashArray;
  }
}
