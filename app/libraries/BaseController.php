<?php

declare(strict_types=1);

namespace Libraries;

use Filters\Filter;

class BaseController
{
  /**
   * @param $view
   * @param array $data
   * @return void
   */
  public function view($view, array $data = []): void
  {
    if (file_exists('../app/views/' . $view . '.php')) {

      require_once '../app/views/' . $view . '.php';
      $html = ob_get_clean();

      $registryFilter = new Filter($html, $data);
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
