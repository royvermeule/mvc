<?php

declare(strict_types=1);

namespace Filters;

class RegistryFilter
{
  private $html;
  private $data;

  public function __construct($html, $data)
  {
    $this->html = $html;
    $this->data = $data;
  }

  public function filter(): string
  {
    $headRegistry = $this->headRegistry();

    $errors = array();

    $html = $this->html;

    $dataVar = array();

    $html = preg_replace_callback(
      '/<d:var\s+name="([^"]+)"\s+value="([^"]+)"\s*\>/',
      function ($match) use (&$dataVar) {
        $name = $match[1];
        $value = $match[2];
        $dataVar[$name] = $value;
      },
      $html
    );

    $this->data = array_merge($this->data, $dataVar);

    $html = preg_replace_callback(
      '/<head\s+([\w\s="]+)>/',
      function ($match) use ($headRegistry, &$errors) {
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
      $html
    );

    $html = preg_replace_callback(
      '/<include\s+([\w\/\s="]+)\/>/',
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
      $html
    );

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

    $pageTypeRegistry = $this->pageTypeRegistry();

    $html = preg_replace_callback(
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
      $html
    );

    $html = preg_replace_callback(
      '/<a:button\s+([\w\s="\/]+)>(.*?)<\/a:button>/',
      function ($match) {
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
        }

        return '';
      },
      $html
    );


    if (!empty($errors)) {
      $errorMessage = '<p>' . implode('</p><p>', $errors) . '</p>';
      echo $errorMessage;
    }

    return $html;
  }

  private function headRegistry(): mixed
  {
    $headRegistry = [
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

    return $headRegistry;
  }

  private function includeRegistry(): mixed
  {
    $includeRegistry = [];

    return $includeRegistry;
  }

  private function dataRegistry($data): mixed
  {
    $data = array_merge($data, [
      'urlroot' => URLROOT,
      'dtz' => DTZ
    ]);

    return $data;
  }

  private function pageTypeRegistry(): mixed
  {
    $typeRegistry = [
      'html_english' => '<!DOCTYPE html>
      <html lang="en">'
    ];

    return $typeRegistry;
  }
}
