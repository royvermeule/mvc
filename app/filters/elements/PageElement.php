<?php

declare(strict_types=1);

namespace Filters\Elements;

use Controllers\ErrorsStorer;

class PageElement
{
  private string $html;

  public function __construct(string $html)
  {
    $this->html = $html;
  }

  /**
   * @return string
   */
  public function page():string
  {
    $pageTypeRegistry = $this->pageTypeRegistry();
    $this->html = preg_replace_callback(
        '/<page\s+([\w\s="]+)\>/',
        function ($match) use ($pageTypeRegistry, &$errors) {
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
            if (array_key_exists($value, $pageTypeRegistry)) {
              return $pageTypeRegistry[$value];
            } else {
              $errors[] = '"' . $value . '" is not included in the pageTypeRegistry().';
            }
          }

          return '';
        },
        $this->html
    );

    if (!empty($errors)) {
      ErrorsStorer::storeErrors($errors);
    }

    return $this->html;
  }

  /**
   * @return string[]
   */
  private function pageTypeRegistry(): array
  {
    return [
        'html_english' => '<!DOCTYPE html>
      <html lang="en">'
    ];
  }
}