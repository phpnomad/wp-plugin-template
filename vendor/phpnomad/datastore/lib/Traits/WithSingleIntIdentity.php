<?php

namespace PHPNomad\Datastore\Traits;

trait WithSingleIntIdentity
{
    protected int $id;

    /**
     * Gets the id
     *
     * @return int
     */
    public function getId(): int
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