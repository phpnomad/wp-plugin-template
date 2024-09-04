<?php

namespace PHPNomad\Database\Abstracts;

use PHPNomad\Database\Factories\Column;
use PHPNomad\Database\Interfaces\HasCharsetProvider;
use PHPNomad\Database\Interfaces\HasCollateProvider;
use PHPNomad\Database\Interfaces\HasGlobalDatabasePrefix;
use PHPNomad\Database\Interfaces\HasLocalDatabasePrefix;
use PHPNomad\Database\Interfaces\Table as CoreTable;
use PHPNomad\Database\Services\TableSchemaService;
use PHPNomad\Utils\Helpers\Arr;
use PHPNomad\Utils\Helpers\Str;

abstract class Table implements CoreTable
{
    protected HasLocalDatabasePrefix $localPrefixProvider;
    protected HasGlobalDatabasePrefix $globalPrefixProvider;
    protected HasCharsetProvider $charsetProvider;
    protected HasCollateProvider $collateProvider;
    protected TableSchemaService $tableSchemaService;

    public function __construct(
        HasLocalDatabasePrefix  $localPrefixProvider,
        HasGlobalDatabasePrefix $globalPrefixProvider,
        HasCharsetProvider      $charsetProvider,
        HasCollateProvider      $collateProvider,
        TableSchemaService      $tableSchemaService
    )
    {
        $this->tableSchemaService = $tableSchemaService;
        $this->localPrefixProvider = $localPrefixProvider;
        $this->globalPrefixProvider = $globalPrefixProvider;
        $this->charsetProvider = $charsetProvider;
        $this->collateProvider = $collateProvider;
    }

    /**
     * Retrieves the database table name.
     *
     * @return string
     */
    public function getName(): string
    {
        return Str::append($this->globalPrefixProvider->getGlobalDatabasePrefix(), '_')
            . Str::append($this->localPrefixProvider->getLocalDatabasePrefix(), '_')
            . $this->getUnprefixedName();
    }

    /** @inheritdoc */
    abstract public function getUnprefixedName(): string;

    /**
     * Get the charset of the table.
     *
     * @return ?string
     */
    public function getCharset(): ?string
    {
        return $this->charsetProvider->getCharset();
    }

    /**
     * Get the collation of the table.
     *
     * @return ?string
     */
    public function getCollation(): ?string
    {
        return $this->collateProvider->getCollation();
    }

    /** @inheritDoc */
    public function getFieldsForIdentity(): array
    {
        return Arr::map($this->tableSchemaService->getPrimaryColumnsForTable($this), fn(Column $column) => $column->getName());
    }
}
