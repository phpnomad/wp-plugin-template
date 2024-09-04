<?php

namespace PHPNomad\Cache\Interfaces;

interface HasCacheKeyPrefix
{
    public function getCacheKeyPrefix(): string;
}