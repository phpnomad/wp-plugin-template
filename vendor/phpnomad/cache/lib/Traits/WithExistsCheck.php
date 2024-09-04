<?php

namespace PHPNomad\Cache\Traits;

use PHPNomad\Cache\Exceptions\CachedItemNotFoundException;

trait WithExistsCheck
{
    /**
     * Returns true if the specified cache item exists.
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        try {
            $this->get($key);
            return true;
        } catch (CachedItemNotFoundException $e) {
            return false;
        }
    }
}