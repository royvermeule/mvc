<?php

declare(strict_types=1);

namespace Filters\Elements;

class IncludeElement
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
   * @return string
   */
  public function include(): string
  {
    $this->html = preg_replace_callback(
        '/<include\s+([\w\/\s="]+)\>/',
        function ($match) use (&$errors) {
          $attributes = array();
          if (preg_match_all('/(\w+)\s*=\s*"([^"]+)"/', $match[1], $attributeMatches, PREG_SET_ORDER)) {
            foreach ($attributeMatches as $attributeMatch) {
              $attributeName = $attributeMatch[1];
              $attributeValue = $attributeMatch[2];
              $attributes[$attributeName] = $attributeValue;
            }
          }

          if (isset($attributes['file'])) {
            $file = $attributes['file'];
            if (file_exists('' . APPROOT . '/' . $file . '.php')) {
              return file_get_contents('' . APPROOT . '/' . $file . '.php');
            } else {
              $errors[] = '"' . $file . '" cannot be found.';
            }
          }

          return '';
        },
        $this->html
    );

    return $this->html;
  }
}
