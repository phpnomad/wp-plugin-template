<?php

namespace PluginNameReplaceMe;

use PluginNameReplaceMe\Core\Initializer as MigratorCoreInitializer;
use PHPNomad\Cache\Traits\WithInstanceCache;
use PHPNomad\Di\Container;
use PHPNomad\Integrations\WordPress\Strategies\WordPressInitializer;
use PHPNomad\Loader\Bootstrapper;
use PHPNomad\Core\Bootstrap\CoreInitializer as PHPNomadCoreInitializer;
use PHPNomad\Loader\Exceptions\LoaderException;

final class Application
{
  use WithInstanceCache;

  protected string $file;

  public function __construct(string $file)
  {
    $this->file = $file;
  }

  /**
   * Gets a new container instance.
   *
   * @return Container
   */
  protected function getContainer(): Container
  {
    return $this->getFromInstanceCache(Container::class, fn() => new Container());
  }

  protected function initBaseDependencies()
  {
    (new Bootstrapper(
      $this->getContainer(),
      new PHPNomadCoreInitializer(),
      new WordPressInitializer(),
      new MigratorCoreInitializer(),
    ))->load();
  }

  /**
   * Sets up the application.
   *
   * @return void
   * @throws LoaderException
   */
  public function init(): void
  {
    $this->initBaseDependencies();

    (new Bootstrapper(
      $this->getContainer(),
    ))->load();
  }

  /**
   * Installs the application.
   *
   * @return void
   * @throws LoaderException
   */
  public function install(): void
  {
    $this->initBaseDependencies();

    (new Bootstrapper(
      $this->getContainer(),
    ))->load();
  }
}
