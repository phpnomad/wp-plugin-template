<?php

namespace PHPNomad\Update\Interfaces;

interface HasUpdates
{
    /**
     * @return class-string<UpgradeRoutine>[]
     */
    public function getRoutines(): array;
}