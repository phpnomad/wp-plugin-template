<?php

namespace PHPNomad\Cache\Services;

use PHPNomad\Cache\Events\CacheMissed;
use PHPNomad\Cache\Exceptions\CachedItemNotFoundException;
use PHPNomad\Cache\Interfaces\CachePolicy;
use PHPNomad\Cache\Interfaces\CacheStrategy;
use PHPNomad\Events\Interfaces\EventStrategy;

class CacheableService
{
    protected EventStrategy $eventStrategy;
    protected CacheStrategy $cacheStrategy;
    protected CachePolicy $cachePolicy;

    public function __construct(EventStrategy $eventStrategy, CacheStrategy $cacheStrategy, CachePolicy $cachePolicy)
    {
        $this->eventStrategy = $eventStrategy;
        $this->cacheStrategy = $cacheStrategy;
        $this->cachePolicy = $cachePolicy;
    }

    public function getWithCache(string $operation, array $context, callable $callback)
    {
        $key = $this->cachePolicy->getCacheKey($context);

        if ($this->cacheStrategy->exists($key) && $this->cachePolicy->shouldCache($operation, $context)) {
            return $this->cacheStrategy->get($key);
        } else {
            $result = $callback();

            $this->eventStrategy->broadcast(new CacheMissed($operation, $context, $result));

            $this->cacheStrategy->set($key, $result, $this->cachePolicy->getTtl($context));

            return $result;
        }
    }

    /**
     * Gets the cache key using the provided context.
     *
     * @param array $context
     * @return mixed
     * @throws CachedItemNotFoundException
     */
    public function get(array $context)
    {
        return $this->cacheStrategy->get($this->cachePolicy->getCacheKey($context));
    }

    /**
     * Sets the cached object.
     *
     * @param string $operation
     * @param array $context
     * @param $value
     * @return void
     */
    public function set(array $context, $value): void
    {
        $this->cacheStrategy->set(
            $this->cachePolicy->getCacheKey($context),
            $value,
            $this->cachePolicy->getTtl($context)
        );
    }

    /**
     * Delete an item from the cache.
     * @param array $context
     * @return void
     */
    public function delete(array $context): void
    {
        $this->cacheStrategy->delete($this->cachePolicy->getCacheKey($context));
    }

    /**
     * @param array $context
     * @return bool
     */
    public function exists(array $context): bool
    {
        return $this->cacheStrategy->exists($this->cachePolicy->getCacheKey($context));
    }
}