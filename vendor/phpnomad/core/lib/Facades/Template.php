<?php

namespace PHPNomad\Core\Facades;

use PHPNomad\Facade\Abstracts\Facade;
use PHPNomad\Singleton\Traits\WithInstance;
use PHPNomad\Template\Exceptions\TemplateNotFound;
use PHPNomad\Template\Interfaces\CanRender;

/**
 * @extends Facade<CanRender>
 */
class Template extends Facade
{
    use WithInstance;

    /**
     * @param string $templatePath
     * @param array $data
     * @return string
     * @throws TemplateNotFound
     */
    public static function render(string $templatePath, array $data = []): string
    {
        return static::instance()->getContainedInstance()->render($templatePath, $data);
    }

    protected function abstractInstance(): string
    {
        return CanRender::class;
    }
}