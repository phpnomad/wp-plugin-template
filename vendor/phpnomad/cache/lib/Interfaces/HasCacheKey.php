<?php

namespace PHPNomad\Cache\Interfaces;

interface HasCacheKey
{
    /**
     * Gets the cache key to be used for the specified operation and context.
     *
     * @param array $context Additional context information, if needed.
     * @return string The cache key.
     */
    public function getCacheKey(array $context): string;
}