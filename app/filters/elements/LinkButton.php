<?php

declare(strict_types=1);

namespace Filters\Elements;

class LinkButton
{
  private string $html;

  public function __construct(string $html)
  {
    $this->html = $html;
  }

  /**
   * @return string
   */
  public function Linkbutton(): string
  {
    $this->html = preg_replace_callback(
        '/<a:button\s+([\w\s="\/]+)>(.*?)<\/a:button>/',
        function ($match) use (&$errors) { // Add the &$errors reference
          $attributes = $match[1];
          $buttonText = $match[2];
          $attributePairs = explode('" ', $attributes);
          $attributeMap = [];
          foreach ($attributePairs as $pair) {
            list($name, $value) = explode('="', $pair);
            $name = trim($name);
            $value = trim($value, '"');
            $attributeMap[$name] = $value;
          }

          if (isset($attributeMap['href'])) {
            $href = htmlspecialchars($attributeMap['href']);
            unset($attributeMap['href']);
            $attributeString = '';
            foreach ($attributeMap as $name => $value) {
              $attributeString .= sprintf(' %s="%s"', $name, htmlspecialchars($value));
            }

            return sprintf('<a href="%s"><button%s>%s</button></a>', $href, $attributeString, $buttonText);
          } else {
            $errors[] = 'A a:button must always contain an href attribute!'; // Add error message to the $errors array
          }

          return '';
        },
        $this->html
    );

    return $this->html;
  }
}
