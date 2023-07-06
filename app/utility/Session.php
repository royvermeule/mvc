<?php

declare(strict_types=1);

namespace Utility;

class Session
{
  public static function startSession(): void
  {
    if (session_status() === 1) {
      session_start();
    }
  }
}