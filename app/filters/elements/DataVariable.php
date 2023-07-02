<?php

declare(strict_types=1);

namespace Filters\Elements;

use Controllers\ErrorsStorer;

class DataVariable
{
  private string $html;

  /**
   * @param string $html
   */
  public function __construct(string $html)
  {
    $this->html = $html;
  }

  /**
   * @return array
   */
  public function dataVariable(): array
  {
    $dataVar = array();

    $this->html = preg_replace_callback(
        '/<d:var\s+name="([^"]+)"\s+value="([^"]+)"\s*\>/',
        function ($match) use (&$dataVar) {
          $name = $match[1];
          $value = $match[2];
          $dataVar[$name] = $value;
        },
        $this->html
    );

    if (!empty($errors)) {
      ErrorsStorer::storeErrors($errors);
    }

    return $dataVar;
  }
}
