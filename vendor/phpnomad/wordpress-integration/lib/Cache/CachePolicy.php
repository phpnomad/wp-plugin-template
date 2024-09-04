<?php

namespace PHPNomad\Integrations\WordPress\Cache;

use PHPNomad\Cache\Enums\Operation;
use PHPNomad\Cache\Factories\HashedCacheKeyFactory;
use PHPNomad\Cache\Interfaces\CachePolicy as CoreCachePolicy;
use PHPNomad\Cache\Interfaces\HasDefaultTtl;

class CachePolicy implements CoreCachePolicy
{
    protected HashedCacheKeyFactory $cacheKeyFactory;
    protected HasDefaultTtl $defaultTtlProvider;

    public function __construct(
        HashedCacheKeyFactory $cacheKeyFactory,
        HasDefaultTtl         $defaultTtl
    )
    {
        $this->cacheKeyFactory = $cacheKeyFactory;
        $this->defaultTtlProvider = $defaultTtl;
    }

    /** @inheritDoc */
    public function shouldCache(string $operation, array $context = []): bool
    {
        if (defined('SIREN_CACHE')) {
            return (bool)SIREN_CACHE;
        }

        if (defined('WP_DEBUG') && WP_DEBUG) {
            return false; // Disable caching if WP_DEBUG is true
        }

        return true;
    }

    /** @inheritDoc */
    public function getCacheKey(array $context): string
    {
        return $this->cacheKeyFactory->getCacheKey($context);
    }

    /** @inheritDoc */
    public function getTtl(array $context = []): ?int
    {
        return $this->defaultTtlProvider->getDefaultTtl();
    }

    /** @inheritDoc */
    public function shouldInvalidate(string $operation, array $context = []): bool
    {
        return Operation::isInvalidatingOperation($operation);
    }
}