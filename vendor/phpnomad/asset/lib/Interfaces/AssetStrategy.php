<?php
namespace PHPNomad\Asset\Interfaces;

interface AssetStrategy
{

    /**
     * Generates the URL for a file using an absolute path.
     *
     * @param string $file The absolute path and filename of the asset file.
     *
     * @return string The URL of the asset file.
     */
    public function getUrlForAbsoluteAsset(string $file): string;

    /**
     * Generates the URL for a file using a relative path and the caller file.
     *
     * @param string $file The relative path of the asset file.
     * @param string $relativeTo The file path this is relative to.
     * @return string The URL of the relative asset.
     */
    public function getUrlForRelativeAsset(string $file, string $relativeTo): string;
}