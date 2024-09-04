<?php

namespace PHPNomad\Database\Providers;

use PHPNomad\Cache\Services\CacheableService;
use PHPNomad\Database\Interfaces\ClauseBuilder;
use PHPNomad\Database\Interfaces\QueryBuilder;
use PHPNomad\Database\Interfaces\QueryStrategy;
use PHPNomad\Logger\Interfaces\LoggerStrategy;

class DatabaseServiceProvider
{
    public LoggerStrategy $loggerStrategy;
    public QueryStrategy $queryStrategy;
    public CacheableService $cacheableService;

    public QueryBuilder $queryBuilder;
    public ClauseBuilder $clauseBuilder;

    public function __construct(
        LoggerStrategy   $loggerStrategy,
        QueryStrategy    $queryStrategy,
        QueryBuilder     $queryBuilder,
        ClauseBuilder    $clauseBuilder,
        CacheableService $cacheableService
    )
    {
        $this->clauseBuilder = $clauseBuilder;
        $this->loggerStrategy = $loggerStrategy;
        $this->queryStrategy = $queryStrategy;
        $this->queryBuilder = $queryBuilder;
        $this->cacheableService = $cacheableService;
    }
}