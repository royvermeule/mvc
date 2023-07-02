<?php

declare(strict_types=1);

namespace Filters\Elements;

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

    return $dataVar;
  }
}
