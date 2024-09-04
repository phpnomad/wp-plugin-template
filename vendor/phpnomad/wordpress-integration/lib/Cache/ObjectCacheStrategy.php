<?php

namespace PHPNomad\Integrations\WordPress\Cache;

use PHPNomad\Cache\Exceptions\CachedItemNotFoundException;
use PHPNomad\Cache\Interfaces\CacheStrategy;
use PHPNomad\Cache\Traits\WithExistsCheck;

class ObjectCacheStrategy implements CacheStrategy
{
    use WithExistsCheck;

    /** @inheritDoc */
    public function get(string $key)
    {
        $found = false;
        $cache = wp_cache_get($key, '', false, $found);

        if (!$found) {
            throw new CachedItemNotFoundException('Cached item ' . $key . ' Was not found');
        }

        return $cache;
    }

    /** @inheritDoc */
    public function set(string $key, $value, ?int $ttl): void
    {
        wp_cache_set($key, $value, '', $ttl);
    }

    /** @inheritDoc */
    public function delete(string $key): void
    {
        wp_cache_delete($key);
    }

    /** @inheritDoc */
    public function clear(): void
    {
        wp_cache_flush();
    }
}