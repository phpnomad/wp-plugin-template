<?php

namespace PHPNomad\Core\Facades;

use PHPNomad\Cache\Exceptions\CachedItemNotFoundException;
use PHPNomad\Cache\Interfaces\CacheStrategy;
use PHPNomad\Facade\Abstracts\Facade;
use PHPNomad\Singleton\Traits\WithInstance;

/**
 * @extends Facade<CacheStrategy>
 */
class Cache extends Facade
{
    use WithInstance;

    /**
     * Fetches an item from the cache or loads it using a callable.
     *
     * @param string $key The cache key
     * @param callable $setter The setter that sets the cache value
     * @param ?int $ttl Time to live for the cached item, null for default TTL
     *
     * @return mixed
     */
    public static function load(string $key, callable $setter, ?int $ttl = null)
    {
        return static::instance()->getContainedInstance()->load($key, $setter, $ttl);
    }

    /**
     * Get an item from the cache.
     *
     * @param string $key
     * @return mixed
     * @throws CachedItemNotFoundException
     */
    public static function get(string $key)
    {
        return static::instance()->getContainedInstance()->get($key);
    }

    /**
     * Set the item to the cache
     *
     * @param string $key the cache key
     * @param mixed $value The cache value
     * @param ?int $ttl The duration. If null, no expiration.
     */
    public static function set(string $key, $value, ?int $ttl): void
    {
        static::instance()->getContainedInstance()->set($key, $value, $ttl);
    }

    protected function abstractInstance(): string
    {
        return CacheStrategy::class;
    }
}