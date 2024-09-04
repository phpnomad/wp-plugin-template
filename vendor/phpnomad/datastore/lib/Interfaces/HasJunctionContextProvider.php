<?php

namespace PHPNomad\Datastore\Interfaces;

interface HasJunctionContextProvider
{
    public function getJunctionContextProvider(): JunctionContextProvider;
}