<?php

namespace PHPNomad\Datastore\Traits;

trait WithSingleStringIdentity
{
    protected string $id;

    /**
     * Gets the id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Gets the int ID.
     *
     * @return array
     */
    public function getIdentity(): array
    {
        return ['id' => $this->getId()];
    }
}