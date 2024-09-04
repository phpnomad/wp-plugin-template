<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Database\Exceptions\TableUpdateFailedException;
use PHPNomad\Database\Factories\Column;
use PHPNomad\Database\Factories\Index;
use PHPNomad\Database\Interfaces\Table;
use PHPNomad\Database\Interfaces\TableUpdateStrategy as CoreTableUpdateStrategy;
use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Integrations\WordPress\Traits\CanModifyWordPressDatabase;
use PHPNomad\Utils\Helpers\Arr;

class TableUpdateStrategy implements CoreTableUpdateStrategy
{
    use CanModifyWordPressDatabase;

    protected array $prepare = [];

    /**
     * @param Table $table
     * @return void
     * @throws TableUpdateFailedException
     */
    public function syncColumns(Table $table): void
    {
        try {
            $query = $this->buildSyncColumnsQuery($table);

            if(!$query){
                return;
            }
            $this->wpdbQuery($query);
        } catch (DatastoreErrorException $e) {
            throw new TableUpdateFailedException($e);
        }
    }

    protected function convertColumnToSql(Column $column): string
    {
        // Get the column name and type
        $columnName = $column->getName();
        $columnType = $column->getType();

        // Handle type arguments (e.g., VARCHAR(255))
        $typeArgs = $column->getTypeArgs();
        if (!empty($typeArgs)) {
            $columnType .= '(' . implode(',', $typeArgs) . ')';
        }

        // Handle attributes (e.g., NOT NULL, DEFAULT 'value')
        $attributes = implode(' ', $column->getAttributes());

        return "`{$columnName}` {$columnType} {$attributes}";
    }

    protected function getCurrentColumns(string $tableName): array
    {
        global $wpdb;
        $query = "SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA
              FROM INFORMATION_SCHEMA.COLUMNS
              WHERE TABLE_NAME = '{$tableName}'";

        $results = $wpdb->get_results($wpdb->prepare($query), ARRAY_A);
        $columns = [];

        foreach ($results as $row) {
            $columns[$row['COLUMN_NAME']] = $row;
        }

        return $columns;
    }

    protected function needsColumnModification(array $currentColumnData, Column $newColumn): bool
    {
        //TODO: THIS ONLY DOES A BASIC CHECK FOR TYPE, DOES NOT INCLUDE OTHER MODIFIER CHECKS

        // Check if the column type and type arguments match
        $currentType = $currentColumnData['COLUMN_TYPE'];
        $newType = $newColumn->getType();

        // Append type arguments to the new type if they exist
        $typeArgs = $newColumn->getTypeArgs();
        if (!empty($typeArgs)) {
            $newType .= '(' . implode(',', $typeArgs) . ')';
        }

        // Check if the types are different
        if (!str_contains(strtolower($currentType), strtolower($newType))) {
            return true;
        }

        return false;
    }

    /**
     * Gets the specified update table
     *
     * @param Table $table
     * @return string
     */
    protected function buildSyncColumnsQuery(Table $table): ?string
    {
        $currentColumns = $this->getCurrentColumns($table->getName());
        $newColumns = $table->getColumns();
        $queries = [];

        // Add or modify columns
        foreach ($newColumns as $newColumn) {
            $columnName = $newColumn->getName();
            if (!array_key_exists($columnName, $currentColumns)) {
                // Column does not exist, add it
                $queries[] = "ADD COLUMN " . $this->convertColumnToSql($newColumn);
            } else {
                // Column exists, check if it needs to be modified
                if ($this->needsColumnModification($currentColumns[$columnName], $newColumn)) {
                    $queries[] = "MODIFY COLUMN " . $this->convertColumnToSql($newColumn);
                }
            }
        }

        $newColumnNames = Arr::pluck($newColumns, 'name');

        // Drop columns that no longer exist in the new definition
        foreach ($currentColumns as $currentColumnName => $currentColumnData) {
            if (!in_array($currentColumnName, $newColumnNames)) {
                $queries[] = "DROP COLUMN `{$currentColumnName}`";
            }
        }

        $args = Arr::process($queries)
            ->whereNotEmpty()
            ->setSeparator(",\n ")
            ->toString();

        if(empty($args)){
            return null;
        }

        return <<<SQL
            ALTER TABLE {$table->getName()} 
            $args 
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
