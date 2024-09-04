<?php

namespace PHPNomad\Database\Factories;

final class Column
{
    protected string $name;
    protected string $type;
    protected ?array $typeArgs = null;
    protected array $attributes = [];

	/**
	 * @param string   $name
	 * @param string   $type
	 * @param array|null $typeArgs
	 * @param          ...$attributes
	 */
    public function __construct(string $name, string $type, ?array $typeArgs = null, ...$attributes)
    {
        $this->name = $name;
        $this->type = $type;
        $this->typeArgs = $typeArgs;
        $this->attributes = $attributes;
    }

    /**
     * Gets the table name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the column type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Returns the length limitation on the item, or null if not set.
     *
     * @return ?array
     */
    public function getTypeArgs(): ?array
    {
        return $this->typeArgs;
    }

    /**
     * @return string[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}