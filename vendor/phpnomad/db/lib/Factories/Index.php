<?php

namespace PHPNomad\Database\Factories;

final class Index
{
    protected ?string $name;
    protected array $columns;
    protected ?string $type = null;
    protected array $attributes = [];

    public function __construct(
        array $columns,
        ?string $name = null,
        ?string $type = null,
        ...$attributes
    ) {
        $this->name = $name;
        $this->columns = $columns;
        $this->type = $type;
        $this->attributes = $attributes;
    }

    /**
     * Gets the index name.
     *
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Gets the columns that are part of the index.
     *
     * @return array|string[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Gets the type of the index.
     *
     * @return ?string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Gets any additional attributes or options for the index.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
