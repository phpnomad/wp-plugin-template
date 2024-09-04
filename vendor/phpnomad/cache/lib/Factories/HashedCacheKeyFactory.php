<?php

namespace PHPNomad\Cache\Factories;

use PHPNomad\Cache\Interfaces\HasCacheKey;
use PHPNomad\Cache\Interfaces\HasCacheKeyPrefix;
use PHPNomad\Logger\Interfaces\LoggerStrategy;
use PHPNomad\Utils\Helpers\Str;

class HashedCacheKeyFactory implements HasCacheKey
{
    protected HasCacheKeyPrefix $cacheKeyPrefixProvider;

    public function __construct(HasCacheKeyPrefix $cacheKeyPrefixProvider, LoggerStrategy $logger)
    {
        $this->cacheKeyPrefixProvider = $cacheKeyPrefixProvider;
        $this->logger = $logger;
    }

    /**
     * Gets the cache key, using a hash
     *
     * @param array $context
     * @return string
     */
    public function getCacheKey(array $context): string
    {
        try {
            return $this->cacheKeyPrefixProvider->getCacheKeyPrefix() . '_' . Str::createHash($context);
        } catch (\ReflectionException $e) {
            $this->logger->logException($e);
        }
    }
}