<?php

namespace PHPNomad\Cache\Traits;

/**
 * Makes it possible to set up a basic caching mechanism localized to the instance.
 */
trait WithInstanceCache
{
    protected array $localInstanceCache = [];

    /**
     * Stores the specified item in the local instance.
     *
     * @param string $key
     * @param callable $callback
     * @return mixed
     */
    public function getFromInstanceCache(string $key, callable $callback)
    {
        if (!isset($this->localInstanceCache[$key])) {
            $this->localInstanceCache[$key] = $callback();
        }

        return $this->localInstanceCache[$key];
    }
}