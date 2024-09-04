<?php

namespace PHPNomad\Core\Bootstrap;

use PHPNomad\Core\Facades\Cache;
use PHPNomad\Core\Facades\Event;
use PHPNomad\Core\Facades\InstanceProvider as InstanceProviderFacade;
use PHPNomad\Core\Facades\Logger;
use PHPNomad\Core\Facades\PathResolver;
use PHPNomad\Core\Facades\Template;
use PHPNomad\Core\Facades\UrlResolver;
use PHPNomad\Core\Strategies\InstanceProviderStrategy;
use PHPNomad\Core\Strategies\Logger as LoggerStrategy;
use PHPNomad\Di\Interfaces\CanSetContainer;
use PHPNomad\Di\Interfaces\InstanceProvider;
use PHPNomad\Di\Traits\HasSettableContainer;
use PHPNomad\Facade\Interfaces\HasFacades;
use PHPNomad\Loader\Interfaces\HasClassDefinitions;
use PHPNomad\Loader\Interfaces\HasLoadCondition;
use PHPNomad\Loader\Interfaces\Loadable;
use PHPNomad\Logger\Interfaces\LoggerStrategy as CoreLoggerStrategy;

final class CoreInitializer implements HasLoadCondition, HasFacades, HasClassDefinitions, CanSetContainer, Loadable
{
    use HasSettableContainer;

    public const REQUIRED_PHP_VERSION = '7.4';
    /**
     * @var string
     */
    protected $phpVersion;

    public function __construct()
    {
        $this->phpVersion = phpversion();
    }

    /** @inheitDoc */
    public function shouldLoad(): bool
    {
        return version_compare($this->phpVersion, static::REQUIRED_PHP_VERSION, '>=');
    }

    /**
     * @return array<Cache|Event|Logger>
     */
    public function getFacades(): array
    {
        return array(
            Logger::instance(),
            Cache::instance(),
            Event::instance(),
            Template::instance(),
            UrlResolver::instance(),
            PathResolver::instance(),
            InstanceProviderFacade::instance()
        );
    }

    /**
     * @return string[]
     */
    public function getClassDefinitions(): array
    {
        return [LoggerStrategy::class => CoreLoggerStrategy::class];
    }

    public function load(): void
    {
        $this->container->bindSingletonFromFactory(InstanceProvider::class, fn() => new InstanceProviderStrategy($this->container));
    }
}
