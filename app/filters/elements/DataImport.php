<?php

declare(strict_types=1);

namespace Filters\Elements;

use Controllers\ErrorsStorer;

class DataImport
{
  private ?string $html;
  private array $data;

  /**
   * @param array $data
   * @param string|null $html
   */
  public function __construct(array $data, ?string $html = null)
  {
    $this->html = $html;
    $this->data = $data;
  }

  /**
   * @return string
   */
  public function Data(): string
  {
    $html = $this->html;

    $dataRegistry = $this->dataRegistry($this->data);

    $html = preg_replace_callback(
      '/{(\w+)}/',
      function ($match) use ($dataRegistry, &$errors) {
        if (array_key_exists($match[1], $dataRegistry)) {
          return $dataRegistry[$match[1]];
        } else {
          $errors[] = '"' . $match[1] . '" is not included in the dataRegistry().';
          return '';
        }
      },
      $html
    );

    if (!empty($errors)) {
      ErrorsStorer::storeErrors($errors);
    }

    return $html;
  }

  /**
   * @return array
   */
  public function dataRegistry(): array
  {
    return array_merge($this->data, [
      'urlroot' => URLROOT,
      'dtz' => DTZ
    ]);
  }
}
