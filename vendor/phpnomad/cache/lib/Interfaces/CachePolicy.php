<?php

namespace PHPNomad\Cache\Interfaces;

use PHPNomad\Cache\Enums\Operation;

interface CachePolicy extends HasCacheKey
{
    /**
     * Determines whether the given data or operation should be cached.
     *
     * @param Operation::* $operation The name or identifier of the operation.
     * @param array $context Additional context information, if needed.
     * @return bool True if should be cached; otherwise, false.
     */
    public function shouldCache(string $operation, array $context = []): bool;

    /**
     * Gets the Time To Live (TTL) for the cache entry for the specified operation.
     *
     * @param Operation::* $operation The name or identifier of the operation.
     * @param array $context Additional context information, if needed.
     * @return int|null The TTL in seconds, or null for default/no expiration.
     */
    public function getTtl(array $context = []): ?int;

    /**
     * Determines whether the cache should be invalidated for the given operation.
     *
     * @param Operation::* $operation The name or identifier of the operation.
     * @param array $context Additional context information, if needed.
     * @return bool True if should invalidate; otherwise, false.
     */
    public function shouldInvalidate(string $operation, array $context = []): bool;
}