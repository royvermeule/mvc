<?php

declare(strict_types=1);

namespace Filters\Elements;

class Head
{
  private $html;
  private $data;

  public function __construct(string $html, array $data) {
    $this->html = $html;
    $this->data = $data;
  }
}
