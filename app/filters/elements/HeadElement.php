<?php

declare(strict_types=1);

namespace Filters\Elements;

use Utility\ErrorsStorer;

class HeadElement
{
  private string $html;

  /**
   * @param string $html
   * @param array $data
   */
  public function __construct(string $html, array $data)
  {
    $this->html = $html;
    $this->data = $data;
  }

  public function head(): string
  {
    $headRegistry = $this->headRegistry();
    $this->html = preg_replace_callback(
        '/<head\s+([\w\s="]+)>/',
        static function ($match) use ($headRegistry, &$errors) {
          $attributes = array();
          if (preg_match_all('/(\w+)\s*=\s*"([^"]+)"/', $match[1], $attributeMatches, PREG_SET_ORDER)) {
            foreach ($attributeMatches as $attributeMatch) {
              $attributeName = $attributeMatch[1];
              $attributeValue = $attributeMatch[2];
              $attributes[$attributeName] = $attributeValue;
            }
          }

          if (isset($attributes['type'])) {
            $value = $attributes['type'];
            if (array_key_exists($value, $headRegistry)) {
              return $headRegistry[$value];
            } else {
              $errors[] = '"' . $value . '" is not included in the headRegistry().';
            }
          }

          return '';
        },
        $this->html
    );

    $dataImport = new DataImport($this->data);
    $dataRegistry = $dataImport->dataRegistry();

    $this->html = preg_replace_callback(
        '/{(\w+)}/',
        static function ($match) use ($dataRegistry, &$errors) {
          if (array_key_exists($match[1], $dataRegistry)) {
            return $dataRegistry[$match[1]];
          } else {
            $errors[] = '"' . $match[1] . '" is not included in the dataRegistry().';
            return '';
          }
        },
        $this->html
    );

    if (!empty($errors)) {
      ErrorsStorer::storeErrors($errors);
    }

    return $this->html;
  }

  /**
   * @return array
   */
  private function headRegistry(): array
  {
    return [
        'html5' => '<head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">',

        'html5_title' => '<head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>{title}</title>'
    ];
  }
}
