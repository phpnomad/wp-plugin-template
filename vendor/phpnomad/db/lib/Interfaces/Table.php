<?php

namespace PHPNomad\Database\Interfaces;

use PHPNomad\Database\Factories\Column;
use PHPNomad\Database\Factories\Index;

interface Table
{
    /**
     * Gets the name of this table. Must include any necessary prefixes.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Gets the alias for this table.
     *
     * @return string
     */
    public function getAlias(): string;

    /**
     * Gets the current version of the table.
     *
     * @return string
     */
    public function getTableVersion(): string;

    /**
     * Gets the list of columns in the table.
     *
     * @return Column[]
     */
    public function getColumns(): array;

    /**
     * Gets the list of columns in the table.
     *
     * @return Index[]
     */
    public function getIndices(): array;

    /**
     * Get the charset of the table.
     *
     * @return ?string
     */
    public function getCharset(): ?string;

    /**
     * Get the collation of the table.
     *
     * @return ?string
     */
    public function getCollation(): ?string;

    /**
     * Gets the list of field names that are required to identify this model.
     *
     * @return non-empty-array<string>
     */
    public function getFieldsForIdentity(): array;

    /**
     * Gets the table name, without a prefix. Should be plural.
     *
     * @return string
     */
    public function getUnprefixedName(): string;

    /**
     * Gets the singular form of the unprefixed name.
     *
     * @return string
     */
    public function getSingularUnprefixedName(): string;
}