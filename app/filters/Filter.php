<?php

declare(strict_types=1);

namespace Filters;

use Filters\Elements\DataImport;
use Filters\Elements\DataVariable;
use Filters\Elements\HeadElement;
use Filters\Elements\IncludeElement;
use Filters\Elements\LinkButton;
use Filters\Elements\PageElement;
use Handlers\ErrorHandler;
use Utility\Encrypter;

class Filter
{
  private string $html;
  private array $data;

  /**
   * @param $html
   * @param $data
   */
  public function __construct($html, $data)
  {
    $this->html = $html;
    $this->data = $data;
  }

  /**
   * @return string
   */
  public function filter(): string
  {
    $html = $this->html;

    $dataVarElement = new DataVariable($this->html);
    $dataVar = $dataVarElement->dataVariable();
    $this->data = array_merge($this->data, $dataVar);

//    $dataImport = new DataImport($this->data, $this->html);
//    $html = $dataImport->data(); Also build into the headElement

    $includeElement = new IncludeElement($html);
    $html = $includeElement->include();

    $pageElement = new PageElement($html);
    $html = $pageElement->page();

    $linkButton = new LinkButton($html);
    $html = $linkButton->Linkbutton();

    $headElement = new HeadElement($html, $this->data);
    $html = $headElement->head();

    if (isset($_SESSION)) {
      $encrypter = new Encrypter();
      $decryptedErrors = $encrypter->decryptArray($_SESSION['errors']);
      echo ErrorHandler::errorPopup($decryptedErrors);
    }

    return $html;
  }
}
