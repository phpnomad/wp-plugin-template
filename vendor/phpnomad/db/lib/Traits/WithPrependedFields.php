<?php

namespace PHPNomad\Database\Traits;

use PHPNomad\Database\Interfaces\Table;

trait WithPrependedFields
{
    use WithUseTable;

    /**
     * Validates that the given table has a column that matches the specified field.
     *
     * @param string $field
     * @param Table|null $table
     * @return bool
     */
    protected function tableHasField(string $field, ?Table $table = null): bool
    {
        $table = $table ?? $this->table;

        foreach ($table->getColumns() as $column) {
            if ($column->getName() === $field) {
                return true;
            }
        }

        return false;
    }

    /**
     * Prepends the specified field with the current table's alias.
     *
     * @param string $field
     * @param ?Table $table
     * @return string
     */
    protected function prependField(string $field, ?Table $table = null): string
    {
        $table = $table ?? $this->table;

        return $table->getAlias() . '.' . $field;
    }
}