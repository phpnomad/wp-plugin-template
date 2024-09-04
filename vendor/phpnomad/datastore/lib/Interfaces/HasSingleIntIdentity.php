<?php

namespace PHPNomad\Datastore\Interfaces;

interface HasSingleIntIdentity
{
    /**
     * @return int
     */
    public function getId(): int;
}