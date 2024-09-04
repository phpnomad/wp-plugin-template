<?php

namespace PHPNomad\Datastore\Interfaces;

interface HasSingleStringIdentity
{
    /**
     * @return string
     */
    public function getId(): string;
}