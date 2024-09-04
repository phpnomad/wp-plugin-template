<?php

namespace PHPNomad\Database\Interfaces;

interface HasLocalDatabasePrefix
{
    /**
     * Gets the database prefix.
     * @return string
     */
    public function getLocalDatabasePrefix(): string;
}