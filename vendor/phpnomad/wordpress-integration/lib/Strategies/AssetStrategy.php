<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Asset\Interfaces\AssetStrategy as AssetStrategyInterface;
use PHPNomad\Utils\Helpers\Str;

class AssetStrategy implements AssetStrategyInterface
{
    /**
     * @inheritDoc
     */
    public function getUrlForRelativeAsset(string $file, string $relativeTo): string {
        $absolutePath = realpath(dirname($relativeTo) . DIRECTORY_SEPARATOR . $file);
        return $this->getUrlForAbsoluteAsset($absolutePath);
    }

    /**
     * @inheritDoc
     */
    public function getUrlForAbsoluteAsset(string $file): string
    {
        $path = $file;
        $file = basename($file);
        return plugin_dir_url($path) . $file;
    }
}