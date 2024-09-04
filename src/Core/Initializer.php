<?php

namespace PluginNameReplaceMe\Core;

use PHPNomad\Core\Events\Ready;
use PHPNomad\Events\Interfaces\HasEventBindings;

class Initializer implements HasEventBindings
{
  /**
   * WordPress can run the init hook more than once, so we need to track when it happens and ensure ready only runs
   * one time.
   *
   * @var bool
   */
  private static bool $initRan = false;

  public function getEventBindings() : array
  {
    return [
      Ready::class => [
        ['action' => 'init', 'transformer' => function () {
          $ready = null;

          if (!self::$initRan) {
            $ready = new Ready();
            self::$initRan = true;
          }

          return $ready;
        }]
      ],
    ];
  }
}