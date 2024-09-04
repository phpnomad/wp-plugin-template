<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Database\Exceptions\TableCreateFailedException;
use PHPNomad\Database\Factories\Column;
use PHPNomad\Database\Factories\Index;
use PHPNomad\Database\Interfaces\Table;
use PHPNomad\Database\Interfaces\TableCreateStrategy as CoreTableCreateStrategy;
use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Integrations\WordPress\Traits\CanModifyWordPressDatabase;
use PHPNomad\Utils\Helpers\Arr;

class TableCreateStrategy implements CoreTableCreateStrategy
{
    use CanModifyWordPressDatabase;

    protected array $prepare = [];

    /**
     * @param Table $table
     * @return void
     * @throws TableCreateFailedException
     */
    public function create(Table $table): void
    {
        try {
            $this->wpdbQuery($this->buildCreateQuery($table));
        } catch (DatastoreErrorException $e) {
            throw new TableCreateFailedException($e);
        }
    }

    /**
     * Gets the specified create table
     *
     * @param Table $table
     * @return string
     */
    protected function buildCreateQuery(Table $table): string
    {
        $args = Arr::process([$this->convertColumnsToSqlString($table), $this->convertIndicesToSqlString($table)])
            ->whereNotEmpty()
            ->setSeparator(",\n ")
            ->toString();

        return <<<SQL
            CREATE TABLE IF NOT EXISTS {$table->getName()} (
                $args
            ) CHARACTER SET {$table->getCharset()} COLLATE {$table->getCollation()};
        SQL;
    }

    protected function convertColumnsToSqlString(Table $table): string
    {
        return Arr::process($table->getColumns())
            ->map(fn(Column $column) => $this->convertColumnToSchemaString($column))
            ->setSeparator(",\n ")
            ->toString();
    }

    protected function convertIndicesToSqlString(Table $table): string
    {
        return Arr::process($table->getIndices())
            ->map(fn(Index $index) => $this->convertIndexToSchemaString($index))
            ->setSeparator(",\n ")
            ->toString();
    }

    /**
     * Converts the specified column into a MySQL formatted string.
     *
     * @param Column $column
     * @return string
     */
    protected function convertColumnToSchemaString(Column $column): string
    {
        $type = $column->getType();
        if ($args = $column->getTypeArgs()) {
            $type .= '(' . implode(',', $args) . ')';
        }

        return Arr::process([
            $column->getName(),
            $type,
        ])
            ->merge($column->getAttributes())
            ->whereNotNull()
            ->setSeparator(' ')
            ->toString();
    }

    protected function convertIndexToSchemaString(Index $index): string
    {
        $pieces = [];

        if ($index->getType()) {
            $pieces[] = strtoupper($index->getType());
        }

        if ($index->getName()) {
            $pieces[] = $index->getName();
        }

        $pieces[] = "(" . implode(', ', $index->getColumns()) . ")";

        return implode(' ', Arr::merge($pieces, $index->getAttributes()));
    }
}
