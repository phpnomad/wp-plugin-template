<?php

namespace PHPNomad\Core\Facades;

use PHPNomad\Facade\Abstracts\Facade;
use PHPNomad\Singleton\Traits\WithInstance;
use PHPNomad\Template\Interfaces\CanRender;
use PHPNomad\Template\Interfaces\CanResolveUrls;

/**
 * @extends Facade<CanResolveUrls>
 */
class UrlResolver extends Facade
{
    use WithInstance;

    public static function getUrl(string $assetName): string
    {
        return static::instance()->getContainedInstance()->getUrl($assetName);
    }

    protected function abstractInstance(): string
    {
        return CanResolveUrls::class;
    }
}