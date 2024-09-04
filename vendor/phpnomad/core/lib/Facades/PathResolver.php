<?php

namespace PHPNomad\Core\Facades;

use PHPNomad\Facade\Abstracts\Facade;
use PHPNomad\Singleton\Traits\WithInstance;
use PHPNomad\Template\Interfaces\CanResolvePaths;

/**
 * @extends Facade<CanResolvePaths>
 */
class PathResolver extends Facade
{
    use WithInstance;

    public static function getPath(string $assetName): string
    {
        return static::instance()->getContainedInstance()->getPath($assetName);
    }

    protected function abstractInstance(): string
    {
        return CanResolvePaths::class;
    }
}