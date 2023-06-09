<?php

declare(strict_types=1);

namespace Filters\Elements;

class DataImport
{
  private $html;
  private $data;

  public function __construct(string $html, array $data)
  {
    $this->html = $html;
    $this->data = $data;
  }

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

    return $html;
  }

  private function dataRegistry($data): mixed
  {
    $data = array_merge($data, [
      'urlroot' => URLROOT,
      'dtz' => DTZ
    ]);

    return $data;
  }
}
