<?php

declare(strict_types=1);

namespace Libraries;

use Filters\RegistryFilter;

class BaseController
{
  /**
   * Checking if the file exists.
   * Then getting the file contents and filtering it.
   */
  public function view($view, $data = [])
  {
    if (file_exists('../app/views/' . $view . '.php')) {

      require_once '../app/views/' . $view . '.php';
      $html = ob_get_clean();

      $registryFilter = new RegistryFilter($html, $data);
      $html = $registryFilter->filter();

      //htmlHiddenComment
      $hiddenCommentPattern = '/!(.*?)!/';
      $hiddenCommentReplacement = '';
      $html = str_replace('</page>', '</html>', $html);
      $html = preg_replace($hiddenCommentPattern, $hiddenCommentReplacement, $html);

      echo $html;
    } else {
      die("View does not exists.");
    }
  }
}
